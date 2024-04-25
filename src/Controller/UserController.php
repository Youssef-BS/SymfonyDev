<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class UserController extends AbstractController
{
  
    // #[Route('/user', name: 'app_user')]
    // public function index(): Response
    // {
    //     return $this->render('admin/index.html.twig', [
    //         'controller_name' => 'UserController',
    //     ]);
    // }

    #[Route('/getAll', name: 'app_listDB')]
    public function getAll(UserRepository $repo) : Response{
        $list = $repo->findAll();
        return $this->render('admin/component/users.html.twig', [
            'users' => $list
        ]);
    }

    #[Route('/userUpdate/{id}', name: 'userUpdate', methods: ['POST'])]
    public function updateUser($id, UserRepository $repo, Request $request): Response {
        // Find the user by id
        $user = $repo->findOneBy(['idUser' => $id]);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
    
        $user->setPrenom($request->request->get('prenom'));
        $user->setAge($request->request->get('age'));
        $user->setEmail($request->request->get('email'));
        $user->setPassword($request->request->get('password'));
        $user->setPhoneNumber($request->request->get('phoneNumber'));
        $role = $request->request->get('role');
        if ($role === 'admin') {
            $user->setIsAdmin(true);
            $user->setIsCoach(false);
        } elseif ($role === 'coach') {
            $user->setIsAdmin(false);
            $user->setIsCoach(true);
        } else {
            $user->setIsAdmin(false);
            $user->setIsCoach(false);
        }
    
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
    
        // Redirect to the user details page
        return $this->redirectToRoute('userDetail', ['id' => $user->getIdUser()]);
    }
    
    // #[Route('/deleteUser' , name : 'deleteUser')]

    #[Route('/deleteUser/{id}', name: 'deleteUser', methods: ['GET' , 'POST'])]
    public function deleteUser($id, UserRepository $repo): Response {
        $user = $repo->findOneBy(['idUser' => $id]);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
    
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_listDB');
    }

    #[Route('/addUserPage', name: 'addUserPage')]
    public function addUserPage(): Response
    {
    
        return $this->render('admin/component/addUser.html.twig');
    }


#[Route('/addUser', name: 'addUser', methods: ['POST'])]
public function addUser(Request $request, EntityManagerInterface $entityManager): Response
{
    $username = $request->request->get('nom');
    $lastName = $request->request->get('prenom');
    $email = $request->request->get('email');
    $age = $request->request->get('age');
    $phoneNumber = $request->request->get('phoneNumber');
    $password = $request->request->get('password');
    $role = $request->request->get('role');
  

    $user = new User();
    $user->setNom($username);
    $user->setPrenom($lastName);
    $user->setEmail($email);
    $user->setAge($age);
    $user->setPhoneNumber($phoneNumber);
    $user->setPassword($password);
    $user->setIsAdmin($role === 'admin'); 
    $user->setIsCoach($role === 'coach'); 

    $entityManager->persist($user);
    $entityManager->flush();

    return $this->redirectToRoute('app_listDB');
}




    #[Route('/analystic', name: 'analystic')]
    public function getAnalystic() : Response{
    return $this->render('admin/component/analystics.html.twig');
    }
    #[Route('/users', name: 'users')]
    public function users(UserRepository $repo) : Response{
        $list = $repo->findAll();
        return $this->render('admin/component/users.html.twig', [
            'users' => $list
        ]);
    }

    #[Route('/programs', name: 'programs')]
    public function programs() : Response{
    return $this->render('admin/component/programs.html.twig');
    }
    #[Route('/gyms', name: 'gyms')]
    public function gyms() : Response{
    return $this->render('admin/component/gyms.html.twig');
    }
    #[Route('/products', name: 'produts')]
    public function products() : Response{
    return $this->render('admin/component/products.html.twig');
    }
    #[Route('/reclamation', name: 'reclamation')]
    public function reclamation() : Response{
    return $this->render('admin/component/reclamation.html.twig');
    }

    #[Route('/userDetail/{id}', name: 'userDetail' , methods: ['GET'])]
    public function userDetails($id, UserRepository $repo): Response {
        $user = $repo->findOneBy(["idUser" => $id]);
        return $this->render('admin/component/userDetails.html.twig', [
            'user' => $user,
        ]);
    }




    #[Route('/productDetails', name: 'productDetails')]
    public function productDetails() : Response{
    return $this->render('admin/component/produitDetails.html.twig');
    }
    #[Route('/gymDetails', name: 'gymDetails')]
    public function gymDetails() : Response{
    return $this->render('admin/component/gymsDetails.html.twig');
    }
    #[Route('/reclamationDetails', name: 'reclamationDetails')]
    public function reclamationDetails() : Response{
    return $this->render('admin/component/reclamationDetails.html.twig');
    }
    #[Route('/programsDetails', name: 'programsDetails')]
    public function programsDetails() : Response{
    return $this->render('admin/component/programsDetails.html.twig');
    }

}