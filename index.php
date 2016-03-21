<?php
require 'vendor/autoload.php';
require 'config/database.php';
require 'api.class.php';

$app = new \Slim\Slim(array(
  'templates.path' => './templates',
  'debug' => false
));

// Set this to your domain
$app->domain = '0bs.de';

$app->container->singleton('api', function () use ($app, $dbConfig) {
  return new API($dbConfig, $app->domain);
});

function APIRequest() {
  $app = \Slim\Slim::getInstance();
  $app->view(new \JsonApiView());
  $app->add(new \JsonApiMiddleware());
  $response = $app->response();
  $response->header('Access-Control-Allow-Origin', '*');
}

/**
 * View pages
 */
$app->get('/', function () use ($app) {
  $app->render('shortener.php', array( 'revision' => 4 ));
})->name("root");

$app->get('/api', function () use ($app) {
  $app->render('docs.php');
});

$app->get('/:id', function ($id) use ($app) {
  $result = $app->api->getLinkById($id);

  if ($result != null) {
    $app->response->redirect($result['urlShortened'], 301);
  }
  else {
    $app->response->redirect($app->urlFor('root'), 303);
  }
})->conditions(array('id' => '\w+'));


/**
 * API Methods
 */
$app->group('/api', 'APIRequest', function () use ($app) {

  $app->get('/getLink/:id', function($id) use ($app) {

    $result = $app->api->getLinkById($id);

    if ($result != null) {
      $app->render(200, $result);
    }
    else {
      $app->render(404, array('error' => true, 'msg' => 'Link has not been found'));
    }
  })->conditions(array('id' => '\w+'));
  
  $app->get('/getQrCode/:id', function($id) use ($app) {

    $result = $app->api->qrCode($id);

    if ($result != null) {
      $response = $app->response();
      $response['Content-Type'] = 'image/png';
      $response->status(200);
      $response->body($result->get('png'));
    }
    else {
      $app->render(404, array('error' => true, 'msg' => 'Link has not been found'));
    }
  })->conditions(array('id' => '\w+'));

  $app->put('/addLink', function() use ($app) {
    $putData = $app->request->put();

    if (array_key_exists('url', $putData)) {
      $url = $putData['url'];
      if ($app->api->validateUrl($url)) {

        if (strpos($url, $app->domain)) {
          $app->render(200, array('msg' => 'Huehue, nice try! <img src="/media/gfx/smtlikethis.jpg" alt="">',
            'zonk' => true));
          return;
        }

        $existingUrl = $app->api->getLinkByUrl($url);

        if ($existingUrl) {
          $app->render(200, $existingUrl);
        }
        else {
          $result = $app->api->addLink($url);

          $app->render(201, $result);
        }
      }
      else {
        $app->render(400, array('error' => true, 'msg' => 'Provided url is not in a valid form'));
      }
    }
    else {
      $app->render(400, array('error' => true, 'msg' => 'Parameter `url` must be set'));
    }

  });
});

$app->run();
