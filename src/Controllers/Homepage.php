<?php declare(strict_types = 1);

namespace NTeste\Controllers;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use NTeste\Template\Renderer;
use NTeste\Controllers\DatabaseConnection;

class Homepage
{
     private $request;
     private $response;
     private $renderer;

    public function __construct(Request $request, Response $response, Renderer $renderer)
    {
        $this->request = $request;
        $this->response = $response;
        $this->renderer = $renderer;        
    }


    public function show()
    {        
        $html = $this->renderer->render('Homepage', []);
        $this->response->setContent($html);
        $this->response->send();
        
    }

    public function cadastrar()
    {
        $request = Request::createFromGlobals();

        try{
            if( $this->request->isMethod('POST')){                

                $nomeCadastro = trim($request->get('cadastro'));

                if(!empty($nomeCadastro)){

                    $dbConnect = new DatabaseConnection();

                    $result = $dbConnect->sqlVerifyUsers($nomeCadastro);

                    if(!empty($result['nome'])){
                            if($nomeCadastro == isset($result['nome'])){
                                $data['error'] = $nomeCadastro.' já cadastrado - NIS '. $result['nis'];
                            }

                    }else{
                            $novoNIS = mt_rand(1111111111, 9999999999);
                            $insert = $dbConnect->sqlRegister($nomeCadastro, $novoNIS);
                            if($insert){
                                $data['success'] = "Cadstro Realizado com Sucesso. NIS número".$novoNIS;
                            }else{
                                $data['error'] = 'Ocorreu um erro tente mais tarde.';
                            }
                        
                        }            

                }else{
                    $data['error'] = 'O Nome não pode ser vazio.';
                }

               

                $html = $this->renderer->render('Homepage', $data);
                $this->response->setContent($html);
                $this->response->send();

            }
        }catch(Exception $e){
            echo 'ERROR: ' . $e->getMessage();

        }
    }

    
}