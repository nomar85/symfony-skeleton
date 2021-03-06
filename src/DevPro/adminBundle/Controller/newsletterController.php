<?php
namespace DevPro\adminBundle\Controller;

use DevPro\adminBundle\DependencyInjection\singleSorter;
use DevPro\adminBundle\Utils\DoctrineClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use DevPro\adminBundle\Entity\Newsletter;;
use DevPro\adminBundle\Form\Type\NewsletterType;
use DevPro\adminBundle\Newsletter\NewsletterManager;




class newsletterController extends Controller
{
    /**
     * @Route("/admin/newsletter", name="backend_newsletter")
     */
    public function indexAction()
    {

        $data = $this->getDoctrine()
            ->getRepository("DevProBackendBundle:Newsletter")
            ->findBy([], ['sort' => 'DESC']);

        $html = $this->container->get('templating')->render(
            'admin/Newsletter/index.html.twig', array(
                "data" => $data
            )
        );

        return new Response($html);
    }


    /**
     * @Route("/admin/newsletter/new", name="backend_newsletter_new")
     */
    public function newAction(Request $request)
    {
        $data = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $data);

        $result = $this->handleFormUpload($form, $request, $data, 'neu');

        if($result)
        {
            return $this->redirectToRoute('backend_newsletter');
        }

        $html = $this->container->get('templating')->render(
            'admin/Blog/new.html.twig', array(
                "data" => '',
                "form" => $form->createView()
            )
        );

        return new Response($html);
    }

    /**
     * @Route("/admin/newslettter/edit/{id}", name="backend_newsletter_edit")
     */
    public function editAction(Request $request, $id)
    {
        $data = $this->getDoctrine()
            ->getRepository('DevProBackendBundle:Blog')
            ->find($id);

        $form = $this->createForm(NewsletterType::class, $data);

        $result = $this->handleFormUpload($form, $request, $data, 'edit');
        if($result)
        {
            return $this->redirectToRoute('backend_newsletter');
        }

        $html = $this->container->get('templating')->render(
            'admin/Blog/edit.html.twig', array(
                "form" => $form->createView()
            )
        );

        return new Response($html);
    }


    /**
     * @Route("/admin/newsletter/delete/{id}", name="backend_newsletter_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $em
            ->getRepository('DevProBackendBundle:Newsletter')
            ->find($id);

        $em->remove($data);
        $em->flush();

        return $this->redirectToRoute('backend_newsletter');
    }

    /**
     * @Route("/admin/newsletter/send/{id}", name="backend_newsletter_send")
     */
    public function sendAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $em
            ->getRepository('DevProBackendBundle:Newsletter')
            ->find($id);

        // get Newsletter Personen
        $recipient = $this->getNewsletterPersonen();

        $mailer = $this->get('app.mailer');
        $result = $mailer->sendmail($data->getContent(), $data->getAbsender(), $recipient);

        $html = $this->container->get('templating')->render(
            'admin/Newsletter/sendStatus.html.twig', array(
                'result' => $result
            )
        );

        return new Response($html);
        //return $this->redirectToRoute('backend_newsletter');
    }

    protected function getNewsletterPersonen()
    {
        $data = $this->getDoctrine()
            ->getRepository('DevProBackendBundle:NewsletterEmpfaenger')
            ->findAll();

        return $data;
    }

    /**
     * @Route("/admin/newsletter/sortup/{id}", name="backend_newsletter_sortup")
     */
    public function sortupAction($id)
    {
        $data = $this->getDoctrine()
            ->getRepository('DevProBackendBundle:Blog')
            ->find($id);

        $sorter = new singleSorter($this->container, 'Newsletter', 'DevProBackendBundle');
        $sorter->setSort($data, 'sortup');
        $sorter->flushSort();

        return $this->redirectToRoute('backend_newsletter');
    }

    /**
     * @Route("/admin/newsletter/sortdown/{id}", name="backend_newsletter_sortdown")
     */
    public function sortdownAction($id)
    {
        $data = $this->getDoctrine()
            ->getRepository('DevProBackendBundle:Newsletter')
            ->find($id);

        $sorter = new singleSorter($this->container, 'Newsletter', 'DevProBackendBundle');
        $sorter->setSort($data, 'sortdown');
        $sorter->flushSort();

        return $this->redirectToRoute('backend_newsletter');
    }

    public function handleFormUpload($form, $request, $task, $action)
    {
        $form->handleRequest($request);
        if ($form->isValid()) {

            $dataObject = $form->getData();

            if($action == 'neu')
            {
                $sort = $this->getSetSort();
                $sort++;
                $dataObject->setSort($sort);
            }


            $em = $this->getDoctrine()->getManager();
            $em->persist($dataObject);
            $em->flush();
            return true;
        }
    }

    public function getSetSort()
    {
        $data = $this->getDoctrine()
            ->getRepository('DevProBackendBundle:Newsletter')
            ->findOneby([], ["id" => "DESC"]);

        if($data)
        {
            $sort = $data->getSort();
        }
        else
        {
            $sort = 1;
        }


        return $sort;
    }
}