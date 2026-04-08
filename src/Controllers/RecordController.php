<?php

namespace App\Controllers;

use App\Models\Record;
use App\Views\RecordView;

class RecordController
{

    private Record $model;
    private RecordView $view;

    public function __construct()
    {
        $this->model = new Record();
        $this->view = new RecordView();
    }

    public function handleRequest(): void
    {
        $action = $_GET['action'] ?? 'index';

        switch ($action) {
            case 'index':
                $this->index();
                break;
            case 'insert':
                $this->showForm();
                break;
            case 'store':
                $this->store();
                break;
            case 'edit':
                $this->showForm((int)($_GET['id'] ?? 0));
                break;
            case 'update':
                $this->update();
                break;
            case 'delete':
                $this->delete();
                break;
            default:
                $this->index();
        }
    }

    private function index(): void
    {
        $records = $this->model->getAll();
        $this->view->renderList($records);
    }

    private function showForm(?int $id = null): void
    {
        $record = $id ? $this->model->getById($id) : null;
        $fields = $this->model->getFormFields();
        $this->view->renderForm($record, $fields, $id);
    }

    private function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->sanitizeInput($_POST);
            if ($this->model->insert($data)) {
                header('Location: ?action=index');
                exit;
            } else {
                echo "Ошибка при добавлении записи";
            }
        }
    }

    private function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
            $id = (int)$_POST['id'];
            $data = $this->sanitizeInput($_POST);
            if ($this->model->update($id, $data)) {
                header('Location: ?action=index');
                exit;
            } else {
                echo "Ошибка при обновлении записи";
            }
        }
    }

    private function delete(): void
    {
        if (!empty($_GET['id'])) {
            $id = (int)$_GET['id'];
            if ($this->model->delete($id)) {
                header('Location: ?action=index');
                exit;
            } else {
                echo "Ошибка при удалении записи";
            }
        }
    }

    /**
     * Базовая санитизация входных данных
     */
    private function sanitizeInput(array $input): array
    {
        $sanitized = [];
        foreach ($input as $key => $value) {
            if ($key === 'id' || $key === 'is_deleted') continue;
            $sanitized[$key] = is_string($value) ? trim($value) : $value;
        }
        return $sanitized;
    }
}
