<?php
// Routes

// $app->get('/[{name}]', function ($request, $response, $args) {
//     // Sample log message
//     $this->logger->info("Slim-Skeleton '/' route");

//     // Render index view
//     return $this->renderer->render($response, 'index.phtml', $args);
// });


$app->get('/test', function ($request, $response, $args) {

    $this->db;
    // $r = new \Src\Models\Article;
    $r = \Src\Models\Article::all();
    foreach ($r as $key => $value) {
        var_dump($value->toArray());
    }
    // var_dump( $this->db->table('articles')->get()->toArray() );


    return '@test';
    // return $this->renderer->render($response, 'index.phtml', $args);
});
