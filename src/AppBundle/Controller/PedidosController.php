<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cliente;
use AppBundle\Entity\Pedido;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class PedidosController extends Controller
{
    /**
     * @Route("/pedido/validacao/{id}", name="pedido_validacao")
     * @Method("GET")
     */
    public function validateAction($id)
    {

        $manager = $this->getDoctrine()->getManager();
        $pedido = $manager->getRepository(Pedido::class)->find($id);

        $data['valido'] = 0;

        if($pedido) {
            $data['valido'] = 1;
            $cliente = $manager->getRepository(Cliente::class)->find($pedido->getCliente());
            $data['email'] = $cliente->getEmail();
            $data['nome'] = $cliente->getNome();
        }


        return $this->json($data);
    }

}