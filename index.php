<?php
header('Content-Type: application/json');

include "config.php";

include "classes/retornos.class.php";
$classeRetorno = new Retornos();

if(isset($_GET["url"])){

    $explode = explode("/", $_GET["url"]);

    $headers = apache_request_headers();

    /* Verificando se a API está ativada, e se o token de verificação existe
    e é válido. */
    if(API_IS_ACTIVE == true && isset($headers["API_TOKEN"]) && $headers['API_TOKEN'] == API_TOKEN){

        switch($explode[0]){

            case "categorias";

                echo $classeRetorno->retornarAll("categorias");

                break;

            case "produtos";

                echo $classeRetorno->retornarAll("produtos");

                break;

            case "usuarios";

                /* Verificando se existe algum dado 2 no endpoint.
                Oq seria equivalente a informação de verificação
                passada pelo cliente. */
                if(isset($explode[1])){

                    /* Verificando se o cliente está passando
                    algum método 3 de endpoint. Oq seria equivalente
                    a passar ummétodo de filtro de busca, como ID, NOME etc... */
                    if(isset($explode[2])){

                        /* Filtros de dados válidos para busca uma
                        busca nessa tabela. */
                        $filtrosBusca = [

                            "nome",
                            "id",
                            "email"
                            
                        ];

                        /* Verificando se o dado passado como filtro,
                        corresponde a um dado permitido. */
                        if(in_array($explode[2], $filtrosBusca)){

                            if($classeRetorno->retornaDado("usuarios", $explode[2], $explode[1]) != false){

                                echo $classeRetorno->retornaDado("usuarios", $explode[2], $explode[1]);
        
                            }else{
        
                                echo json_encode([
        
                                    "status" => API_IS_ACTIVE,
                                    "Versao" => API_VERSION,
                                    "msg" => "Sucesso",
                                    "data" => false
                            
                                ]);
        
                            }

                        }else{

                            echo json_encode([
    
                                "status" => API_IS_ACTIVE,
                                "Versao" => API_VERSION,
                                "msg" => "Inclua um metodo valido de busca.",
                                "data" => false
                        
                            ]);

                        }

                    }else{

                        echo json_encode([
    
                            "status" => API_IS_ACTIVE,
                            "Versao" => API_VERSION,
                            "msg" => "Insira um metodo de busca.",
                            "data" => false
                    
                        ]);

                    }

                }else{

                    echo $classeRetorno->retornarAll("usuarios");

                }

                break;

            case "vendas";

                echo $classeRetorno->retornarAll("vendas");

                break;

        }

    }else{

        echo json_encode([
    
            "status" => API_IS_ACTIVE,
            "Versao" => API_VERSION,
            "msg" => "Dados de verificação não conferem, ou existe algum erro interno",
            "data" => false
    
        ]);

    }

}else{

    echo json_encode([
    
        "status" => API_IS_ACTIVE,
        "Versao" => API_VERSION,
        "msg" => "URL da API não existente",
        "data" => false

    ]);

}

?>