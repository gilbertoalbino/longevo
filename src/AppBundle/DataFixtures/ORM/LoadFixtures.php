<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Chamado;
use AppBundle\Entity\Cliente;
use AppBundle\Entity\Pedido;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {

        /**
         * Cria os clientes iniciais
         */
        $clientes = [];
        for ($i = 1; $i <= 10; $i++) {

            $cliente = new Cliente();
            $cliente->setNome("Cliente $i");
            $cliente->setEmail("cliente_$i@example.com");

            $manager->persist($cliente);
            $manager->flush();

            $idCliente = $cliente->getId();

            $cliente->setNome("Cliente $idCliente");
            $cliente->setEmail("cliente_$idCliente@example.com");


            $manager->persist($cliente);
            $manager->flush();

            $clientes[] = $idCliente;
        }

        /*
         * Cria os pedidos iniciais.
         */
        $pedidos = [];
        shuffle($clientes);

        for ($i = 0; $i < 10; $i++) {

            $pedido = new Pedido();

            $clienteObj = $manager->getRepository('AppBundle\Entity\Cliente')->find($clientes[$i]);

            $pedido->setCliente($clienteObj);

            $manager->persist($pedido);
            $manager->flush();

            $pedidos[] = $pedido->getId();
        }

        shuffle($pedidos);

        for ($i = 0; $i < 10; $i++) {

            $chamado = new Chamado();

            $idChamado = $i + 1;

            $pedidoObj = $manager->getRepository('AppBundle\Entity\Pedido')->find($pedidos[$i]);
            $clienteObj = $manager->getRepository('AppBundle\Entity\Cliente')->find($pedidoObj->getCliente()->getId());

            $chamado->setCliente($clienteObj);
            $chamado->setEmail($clienteObj->getEmail());
            $chamado->setPedido($pedidoObj);
            $chamado->setTitulo("Chamado teste #$idChamado");
            $chamado->setObservacao("Observação do chamado teste #$idChamado");

            $manager->persist($chamado);
            $manager->flush();
        }

    }
}