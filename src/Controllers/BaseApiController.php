<?php

namespace APPKITA\CI4_REST\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


class BaseApiController extends ResourceController
{
    protected $request;
    protected $helpers = [];
    protected $modelName;
    protected $format = 'json';
    protected $validate = [];
    protected $validate_update = [];
    protected $publicAccessMethods = ['index', 'show', 'datatable'];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->setModel($this->modelName);
    }
    public function _remap($method, ...$params)
    {
        $allow = false;
        if ($this->publicAccessMethods == '*') {
            $allow = true;
        } else {
            if (is_string($this->publicAccessMethods)) {
                $this->publicAccessMethods = [$this->publicAccessMethods];
            }
            if (in_array($method, $this->publicAccessMethods)) {
                $allow = true;
            }
        }
        if ($allow === false) {
            if (session()->get('isLoggedIn')) {
                $allow = true;
            }
        }
        if (method_exists($this, $method) && $allow === true) {
            return $this->{$method}(...$params);
        }

        return $this->respondWithFormat(false, 404, 'Page not Found');
    }
    protected function respondWithFormat($status, $code, $message, $data = null)
    {
        $response = [
            'status' => $status,
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];

        return $this->respond($response, $code);
    }
    public function index()
    {
        $data = $this->model->findAll();
        return $this->respondWithFormat(true, 200, 'Data retrieved successfully', $data);
    }

    public function show($id = null)
    {
        $data = $this->model->find($id);
        if (!$data) {
            return $this->respondWithFormat(false, 404, 'Data not found');
        }
        return $this->respondWithFormat(true, 200, 'Data retrieved successfully', $data);
    }

    public function datatable()
    {
        $searchValue = $this->request->getGet('search')['value'] ?? '';
        $start = $this->request->getGet('start') ?? 0;
        $length = $this->request->getGet('length') ?? 10;

        // Ambil parameter sorting dari DataTables
        $order = $this->request->getGet('order')[0] ?? null;
        $orderColumnIndex = $order['column'] ?? 0;
        $orderDirection = $order['dir'] ?? 'ASC'; // ASC atau DESC

        $orderColumn =  null;
        $columns = $this->request->getGet('columns');
        if (!empty($columns) && is_array($columns)) {
            if (isset($columns[$orderColumnIndex])) {
                $orderColumn = $columns[$orderColumnIndex]['data'];
            }
        }

        $data = $this->model->get_datatables($searchValue, $start, $length, $orderColumn, $orderDirection);

        $totalFiltered = $this->model->get_filtered_count($searchValue);
        $totalRecords = $this->model->get_all_count();

        return $this->respond([
            'status' => true,
            'code' => 200,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    public function create()
    {
        $data = $this->request->getPost();

        if ($this->request->getHeaderLine('Content-Type') === 'application/json') {
            $data = $this->request->getJSON(true);
        }
        $primary = $this->model->getAttributePrimary();
        $id = $this->request->getPost($primary);
        if (!empty($id)) {
            if (!empty($this->validate_update) && !$this->validate($this->validate_update)) {
                return $this->respondWithFormat(false, 422, 'Validation errors', $this->validator->getErrors());
            }
            if ($this->model->update($id, $data)) {
                return $this->respondWithFormat(true, 200, 'Data updated successfully', $data);
            } else {
                return $this->respondWithFormat(false, 500, 'Failed to update data', $this->model->errors());
            }
        }


        if (!empty($this->validate) && !$this->validate($this->validate)) {
            return $this->respondWithFormat(false, 422, 'Validation errors', $this->validator->getErrors());
        }
        if ($this->model->insert($data)) {
            return $this->respondWithFormat(true, 201, 'Data created successfully', $data);
        }

        return $this->respondWithFormat(false, 500, 'Failed to create data', $this->model->errors());
    }

    public function update($id = null)
    {
        $data = $this->request->getRawInput();
        if (!$this->model->find($id)) {
            return $this->respondWithFormat(false, 404, 'Data not found');
        }
        if (!empty($this->validate_update) && !$this->validate($this->validate_update)) {
            return $this->respondWithFormat(false, 422, 'Validation errors', $this->validator->getErrors());
        }

        if ($this->model->update($id, $data)) {
            return $this->respondWithFormat(true, 200, 'Data updated successfully', $data);
        }

        return $this->respondWithFormat(false, 500, 'Failed to update data', $this->model->errors());
    }

    public function delete($id = null)
    {
        $data = $this->model->find($id);
        if (!$data) {
            return $this->respondWithFormat(false, 404, 'Data not found');
        }

        if ($this->model->delete($id)) {
            return $this->respondWithFormat(true, 200, 'Data deleted successfully', $data);
        }

        return $this->respondWithFormat(false, 500, 'Failed to delete data');
    }
}
