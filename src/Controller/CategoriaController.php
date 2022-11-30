<?php

declare(strict_types=1);


namespace App\Controller;

use App\Model\Categoria;
use App\Repository\CategoriaRepository;
use Dompdf\Dompdf;
use Exception;

class CategoriaController extends AbstractController
{
    private CategoriaRepository $repository;

    public function __construct()
    {
        $this->repository = new CategoriaRepository();
    }

    public function listar(): void
    {
        // $repository = new CategoriaRepository();
        $categorias = $this->repository->buscarTodos();
        $this->render('categoria/listar', [
            'categorias' => $categorias,
        ]);
    }

    public function cadastrar(): void
    {
        if(true === empty($_POST)){
            $this->render('categoria/cadastrar');
            return;
        }

        $categoria = new Categoria();
        $categoria->nome = $_POST['nome'];

        $repository = new CategoriaRepository();
        
        try {
            $repository->inserir($categoria);
        } catch (Exception $exception) {
            if (true === str_contains($exception->getMessage(), 'categoria')) {
                die('Esta categoria já existe');
            }
            
            die('Vish, aconteceu um erro');
        }
        

        $this->redirect('/categorias/listar');
    }

    public function editar(): void
    {
        $id = $_GET['id'];
        $rep = new CategoriaRepository();
        $categoria = $rep->buscarUm($id);
        $this->render('categoria/editar', [$categoria]);

        if (false === empty($_POST)) {
            $categoria->nome = $_POST['nome'];
            $rep->atualizar($categoria, $id);

            $this->redirect('/categorias/listar');
        }
    }

    public function excluir(): void
    {
        $id = $_GET['id'];
        // $repository = new CategoriaRepository();
        $this->repository->excluir($id);
        $this->redirect("\categorias\listar");
    }

    private function redirecionar(iterable $categorias){
        $resultado = '';
        foreach ($categorias as $categoria) {
        $resultado .= "
            <tr>
                <td>{$categoria->id}</td>
                <td>{$categoria->nome}</td>
            </tr>";
            }
            return $resultado;
    }

    public function relatorio(): void
    {
        $hoje = date('d/m/Y');
        $categorias = $this->repository->buscarTodos();
        $design = "
        <h1>Relatorio de Categoria</h1>
        <hr>
        <em>Gerando em {$hoje}</em>
        <hr>
        <table border='1' width='100%' style='margin-top: 30px;'>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                </tr>
            </thead>
            <tbody>
            ".$this->redirecionar($categorias)."
            </tbody>
        </table>
        ";

        $dompdf = new Dompdf();
        $dompdf->setPaper('A4', 'portrait'); 
        $dompdf->loadHtml(($design)); 
        $dompdf->render();
        $dompdf->stream('Relatorio-Categorias.pdf', ['Attachment' => 0]);
    }
}
