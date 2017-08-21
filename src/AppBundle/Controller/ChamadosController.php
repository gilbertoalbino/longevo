<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Chamado;
use Respect\Validation\Validator as v;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ChamadosController extends Controller
{
    /**
     * @Route("/chamados", name="chamados_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {

        $filtro = $request->get('filtro');

        $sql = sprintf("
            SELECT h.id, h.email, h.titulo, p.id idPedido, c.nome FROM AppBundle:Chamado h 
            JOIN AppBundle:Pedido p WITH p.id = h.pedido
            JOIN AppBundle:Cliente c WITH c.id = p.cliente
        ");

        if ($filtro != '') {
            $sql .= sprintf("
                WHERE h.email = '%s' OR p.id = '%s'",
                $filtro,
                (((int)$filtro > 0) ? $filtro : -1)
            );
        }

        $query = $this->getDoctrine()
            ->getManager()
            ->createQuery($sql);

        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('chamados/index.html.twig', [
            'filtro' => $request->get('filtro'),
            'pagination' => $pagination,
            ' label_previous' => 'anterior'
        ]);
    }

    /**
     * @Route("/chamados/create", name="chamados_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $pedido = $request->get('pedido');
        $nome = $request->get('nome');
        $email = $request->get('email');
        $titulo = $request->get('titulo');
        $observacao = $request->get('observacao');

        $errors = [];

        if (!v::notEmpty()->validate($pedido)) {
            $errors['pedido'] = 'Informe o pedido';
        }

        if (!v::notEmpty()->validate($nome)) {
            $errors['nome'] = 'Informe o nome';
        }

        if (!v::email()->validate($email)) {
            $errors['email'] = 'E-mail inválido';
        }

        if (!v::notEmpty()->validate($titulo)) {
            $errors['titulo'] = 'Informe o título';
        }

        if (!v::notEmpty()->validate($observacao)) {
            $errors['observacao'] = 'Forneca uma observação';
        }

        if (count($errors) == 0) {

            $em = $this->getDoctrine()->getManager();

            $pedidoObj = $em->getRepository('AppBundle\Entity\Pedido')->find($pedido);
            $clienteObj = $em->getRepository('AppBundle\Entity\Cliente')->find($pedidoObj->getCliente());

            $chamado = new Chamado();

            $chamado->setPedido($pedidoObj);
            $chamado->setCliente($clienteObj);
            $chamado->setEmail($email);
            $chamado->setTitulo(filter_var($titulo, FILTER_SANITIZE_STRING));
            $chamado->setObservacao(filter_var($observacao, FILTER_SANITIZE_STRING));

            $em->persist($chamado);
            $em->flush();
        }

        return $this->json(['errors' => $errors]);

    }
}