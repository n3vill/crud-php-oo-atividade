<?php

declare(strict_types=1);

// require "../Model/Render.php";

// use Render;

class AlunoController
{
    public function listar(): void
    {
        $this->renderizar('listar');
    }

    public function cadastrar(): void
    {
        $this->renderizar('cadastrar');
    }

    public function excluir(): void
    {
        $this->renderizar('excluir');
    }

    public function editar(): void
    {
        $this->renderizar('editar');
    }

    public function renderizar(string $arquivo, ?array $dados = null)
    {
        include "../Views/template/header.phtml";
        include "../Views/aluno/{$arquivo}.phtml";
        $dados;

        include "../Views/template/footer.phtml";
    }
}