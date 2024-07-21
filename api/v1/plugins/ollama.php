<?php
function ollama_add_post($post_permalink, $post_date, $post_title, $post_content, $author = null)
{
    if (defined("ollama_server") && strlen(ollama_server) > 4 && defined("ollama_model")) {
        $data = ["a" => "learn", "ollama_model" => ollama_model, "post_permalink" => $post_permalink, "post_title" => $post_title, "post_content" => $post_content, "post_date" => $post_date, "author" => $author];
        ollama_send_data($data);
    }
}
function ollama_send_data($data)
{
    $curl = curl_init();
    curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL => ollama_server,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        )
    );

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;

}