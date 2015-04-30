<?php
/**
 * Created by PhpStorm.
 * User: Sergio
 * Date: 20/04/2015
 * Time: 1:35
 */

namespace articulosBundle\Controller;


use articulosBundle\Entity\Articulos;
use articulosBundle\Form\BuscarAticuloType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ArticulosController extends Controller
{
    var $articulos = [['id' => 1,
        'nombre' => "flexo",
        'precio' => 25],
        ['id' => 2,
            'nombre' => "silla",
            'precio' => 50],
        ['id' => 3,
            'nombre' => "mesa",
            'precio' => 100],
        ['id' => 4,
            'nombre' => "ordenador",
            'precio' => 400]];

    public function listarAction()
    {
        return $this->render('articulosBundle:Default:listaArticulos.html.twig', array('articulos' => $this->articulos));
    }

    /*public function buscaIdAction($id)
    {
        foreach ($this->articulos as $articulo) {
            if ($articulo['id'] == $id) {
                $articuloSeleccionado = $articulo;
                break;
            }
        }
        return $this->render('articulosBundle:Default:articulo.html.twig', array('articulo' => $articulo));
    }*/

    public function buscaIdAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $articulo = $em->getRepository('articulosBundle:Articulos')->findOneById($id);
        if (count($articulo) != 0) {
            return $this->render('articulosBundle:Default:articulo.html.twig', array('articulo' => $articulo));
        } else {
            $this->get('session')->getFlashBag()->add(
                'NoExiste',
                'No existe el articulo.
                '
            );
            //throw new InvalidArgumentException;
            return $this->render('articulosBundle:Default:articulo.html.twig', array('articulo' => $articulo));
        }
    }

    /*public function buscaNombreAction(Request $peticion, $nombre)
    {
        $articuloSeleccionado = null;
        foreach ($this->articulos as $articulo) {
            if (strcmp(strtolower($articulo['nombre']), strtolower($nombre)) == 0) {
                $articuloSeleccionado = $articulo;
                break;
            }
        }
        if ($articuloSeleccionado == null) {
            throw new \InvalidArgumentException;
        }
        return $this->render('articulosBundle:Default:articulo.html.twig', array('articulo' => $articulo));
    }*/
    public function buscaNombreAction(Request $peticion, $nombre)
    {
        $em = $this->getDoctrine()->getManager();
        $articulo = $em->getRepository('articulosBundle:Articulos')->findOneByNombre($nombre);
        if (count($articulo) != 0) {
            return $this->render('articulosBundle:Default:articulo.html.twig', array('articulo' => $articulo));
        } else {
            $this->get('session')->getFlashBag()->add(
                'NoExiste',
                'No existe el articulo.'
            );
            //throw new InvalidArgumentException;
            return $this->render('articulosBundle:Default:articulo.html.twig', array('articulo' => $articulo));
        }
    }

    public function buscarAction(Request $peticion)
    {
        //$data = new Articulos();
        $form = $this->createForm(new BuscarAticuloType());
        $form->handleRequest($peticion);
        if ($form->isValid()) {
            $data = $form->getData();

            return $this->redirect($this->generateUrl('articulos_lista_nombre',array('nombre'=>$data['product'])));
        }
        return $this->render('articulosBundle:Default:buscar.html.twig', array('form' => $form->createView()));
    }
}