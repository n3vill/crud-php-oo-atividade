<?php

    abstract class Render{

        abstract function renderizar(string $arquivo, ?array $dados = null);
        public function teste()
        {
            return "teste";
        }
        
    }