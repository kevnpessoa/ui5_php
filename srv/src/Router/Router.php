<?php

namespace Napps\Rest\Router;

class Router
{
    private $collection;
    private $path;
    private $method;

    public function __construct(string $path, string $method)
    {
        $this->collection = new RouterCollection();
        $this->path = $path;
        $this->method = $method;
    }

    public function get($path, $callback)
    {
        $this->request('GET', $path, $callback);
    }

    public function post($path, $callback)
    {
        $this->request('POST', $path, $callback);
    }

    public function put($path, $callback)
    {
        $this->request('PUT', $path, $callback);
    }

    public function delete($path, $callback)
    {
        $this->request('DELETE', $path, $callback);
    }

    public function request($method, $path, $callback)
    {
        $this->collection->add($method, $path, $callback);
    }

    public function run() : array
    {
        $callback = null;
        $result = [];
        $data = $this->collection->filter($this->method);

        foreach ($data as $key => $value) {
            $result = $this->checkUrl($key, $this->path);
            $callback = $value;

            if ($result['result']) {
                break;
            }
        }

        $this->reindexResultParams($result);
        $this->checkMethodParams($result);

        if (!$result['result']) {
            $callback = null;
        }
        return [
            'params' => $result['params'],
            'callback' => $callback
        ];
    }

    public function checkUrl(string $toFind, $subject)
    {
        preg_match_all('/\{([^\}]*)\}/', $toFind, $variables);

        $regex = str_replace('/', '\/', $toFind);

        foreach($variables[1] as $key => $value) {
            $as = explode(':', $value);
            $replacement = $as[1] ?? '([a-zA-Z0-9\-\_\ ]+)';
            $regex = str_replace($variables[$key], $replacement, $regex);
        }
        $regex = preg_replace('/{([a-zA-Z]+)}/', '([a-zA-Z0-9+])', $regex);
        $result = preg_match('/^' . $regex . '$/', $subject, $params);

        return compact('result', 'params');
    }

    public function checkMethodParams(array &$result)
    {
        if ($this->method === "GET") {
            foreach ($_GET as $key => $value) {
                if (("/" . $key) != $result['params']['functionUrl']) {
                    $result['params'][$key] = $value;
                }
            }
        }

        if ($this->method === "POST") {
            foreach ($_POST as $key => $value) {
                $result['params'][$key] = $value;
            }
        }

        if (in_array($this->method, ["PUT", "DELETE"])) {
            parse_str(file_get_contents("php://input"),$put);
            foreach ($put as $key => $value) {
                $result['params'][$key] = $value;
            }
        }
    }

    public function reindexResultParams(array &$result)
    {
        $params = $result['params'];
        $result['params'] = [];
        $result['params']['functionUrl'] = $params[0];
        array_shift($params);

        for ($i = 0; $i < count($params); $i++) {
            $result['params']['param' . ($i + 1)] = $params[$i];
        }
    }
}
