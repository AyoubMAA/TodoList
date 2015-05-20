<?php

namespace MSIM\TodoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MSIM\TodoBundle\Entity\Todo;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class TodoController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
		    'SELECT p
		    FROM MSIMTodoBundle:Todo p
		    WHERE p.dateT <= CURRENT_TIMESTAMP()');

		$todos = $query->getResult();
		foreach ($todos as $key => $value) {
			$em->remove($value);
			$em->flush();
		}


    	$todos=$em->getRepository('MSIMTodoBundle:Todo')->findall();
        return $this->render('MSIMTodoBundle:Todo:index.html.twig', array('todos' => $todos));
    }

    public function addAction(){

    	$request = $this->getRequest();
        

      	if($request->isMethod('POST')){

            //$session = $request->getSession();
           // $session->set('',)
            $nom = $request->request->get('nom');
            $dateT = $request->request->get('dateT');
            $timeT = $request->request->get('timeT');
            $desc = $request->request->get('description');

            $todo=new Todo;

            $todo->setNom($nom);
            $date =new \DateTime($dateT.' '.$timeT);
            $todo->setDateT($date);
            $todo->setDescription($desc);
            $em = $this->getDoctrine()->getManager();
           	$em->persist($todo);
        	$em->flush();
        	return $this->render("MSIMTodoBundle:Todo:view.html.twig",array("todo"=>$todo));

         }
        $date=new \DateTime();
    	return $this->render('MSIMTodoBundle:Todo:add.html.twig',array("dateA"=>$date->format('Y-m-d')));
    }

    public function viewAction($id){
    	//die($id);
    	$em = $this->getDoctrine()->getManager();
    	$todo=$em->getRepository('MSIMTodoBundle:Todo')->find($id);
    	//die($todo);
    	if(!$todo){
    		return new Response("Tâche invalide !");
    	}
        return $this->render('MSIMTodoBundle:Todo:view.html.twig', array('todo' => $todo));
    }

    
}
