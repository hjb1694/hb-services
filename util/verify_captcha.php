<?php

function verifyCaptchaToken(string $token, string $ip): array {
  $payload = http_build_query([
    "secret" => $_ENV['CAPTCHA_SECRET'],
    "response" => $token,
    "remoteip" => $ip,
    "sitekey" => "2db6d9bc-9436-4d54-a452-56a489840b7c",
  ]);
  $ctx = stream_context_create([
    "http" => [
      "method" => "POST",
      "header" => "Content-type: application/x-www-form-urlencoded\r\n",
      "content" => $payload,
      "timeout" => 5,
    ],
  ]);
  $raw = file_get_contents(
    "https://api.hcaptcha.com/siteverify",
    false,
    $ctx
  );
  $j = json_decode($raw, true);
  if (!empty($j["success"])) {
    return [true, []];
  }
  return [false, $j["error-codes"] ?? []];
}


?>