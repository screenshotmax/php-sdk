<?php
namespace ScreenshotMax;

class ApiClient
{
    private string $baseUrl = 'https://api.screenshotmax.com';
    private string $accessKey;
    private string $secretKey;

    public function __construct(string $accessKey, string $secretKey)
    {
        if (empty($accessKey) || empty($secretKey)) {
            throw new \InvalidArgumentException('Access and secret keys must both be provided and non-empty.');
        }
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }

    private function signRequest(string $str): string
    {
        return hash_hmac('sha256', $str, $this->secretKey);
    }

    private function computeQuery(array $obj): string
    {
        $filtered = array_filter($obj, fn($v) => $v !== null);
        return http_build_query($filtered);
    }

    public function generateUrl(string $path, array $params = []): string
    {
        $query = $this->computeQuery(array_merge($params, ['access_key' => $this->accessKey]));
        return $this->baseUrl . $path . '?' . $query;
    }

    public function generateSignedUrl(string $path, array $params = []): string
    {
        $query = $this->computeQuery(array_merge($params, ['access_key' => $this->accessKey]));
        $signature = $this->signRequest($query);
        return $this->baseUrl . $path . '?' . $query . '&signature=' . $signature;
    }

    private function exec(string $method, string $url, array $options = [], array $headers = []): array
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($options));
        } elseif ($method === 'PATCH' || $method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if ($method === 'PATCH') {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($options));
            }
        }

        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);
        if ($response === false) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new \RuntimeException($err);
        }

        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headerString = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        $parsedHeaders = [];
        foreach (explode("\r\n", trim($headerString)) as $line) {
            if (strpos($line, ':') !== false) {
                [$k, $v] = explode(':', $line, 2);
                $parsedHeaders[trim($k)] = trim($v);
            }
        }
        curl_close($ch);

        return ['data' => $body, 'headers' => $parsedHeaders];
    }

    public function get(string $path, array $options = [], bool $signed = false): array
    {
        $url = $signed ? $this->generateSignedUrl($path, $options) : $this->generateUrl($path, $options);
        return $this->exec('GET', $url);
    }

    public function post(string $path, array $options = []): array
    {
        $url = $this->baseUrl . $path;
        return $this->exec('POST', $url, $options, [
            'Content-Type: application/json',
            'X-Access-Key: ' . $this->accessKey,
        ]);
    }

    public function delete(string $path): void
    {
        $url = $this->baseUrl . $path;
        $this->exec('DELETE', $url, [], [
            'X-Access-Key: ' . $this->accessKey,
        ]);
    }

    public function patch(string $path, array $options = []): array
    {
        $url = $this->baseUrl . $path;
        return $this->exec('PATCH', $url, $options, [
            'Content-Type: application/json',
            'X-Access-Key: ' . $this->accessKey,
        ]);
    }
}