<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\RoomType;
use App\Form\RoomTypeType;

/**
 * @Route("/room/type", name="room_type")
 */
class RoomTypeController extends Controller
{
    /**
     * @Route("", name="_index")
     */
    public function index()
    {
        return new Response("test");
    }

    /**
     * @Route("/manage", name="_manage")
     */
    public function manage(Request $request)
    {
        $roomType = new RoomType();

        $form = $this->createForm(RoomTypeType::class, $roomType, array(
            'method' => 'POST',
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $roomType = $form->getData();

            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($roomType);
                $em->flush();

                $this->addFlash(
                    'success',
                    'Successfully created '.$roomType->getType()
                );

                return $this->redirectToRoute('room_type_manage');
            } catch (\Exception $e) {
                $this->addFlash(
                    'danger',
                    'room_type.create.failed'
                );
            }
        }

        $roomTypes = $this->getDoctrine()->getRepository(RoomType::class)->findAll();

        return $this->render('admin/manage.html.twig', [
            'type' => 'room_type',
            'primary' => 'id',
            'entities' => $roomTypes,
            'form' => $form->createView(),
            'display' => [
                'id' => 'primary',
                'type' => 'text',
                'capacity' => 'text',
                'created' => 'date',
                'updated' => 'date'
            ]
        ]);
    }
}
