<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;

class DestinationControllerTest extends WebTestCase
{
    private function findOrCreateUser($container)
    {
        $entityManager = $container->get('doctrine')->getManager();
        if($user = $entityManager->getRepository(User::class)->findOneBy(['email' => 'test@test.com'])) {
            return $user;
        }
        // Create a user and persist it to the database
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $user->setEmail("test@test.com");
        $user->setPassword('password'); // Ensure to set a password
        $entityManager->persist($user);
        $entityManager->flush();
        return $user;
    }

    public function testDestinationWithLoginUser(): void
    {
        $client = static::createClient();

        // Create a user and persist to the test database
        $user = $this->findOrCreateUser($client->getContainer());

        // Authenticate the user
        $client->loginUser($user);
        // Access login path
        $crawler = $client->request('GET', '/admin/destination/new');
        $this->assertResponseStatusCodeSame(200);

        $crawler = $client->request('GET', '/admin/destination/');
        $this->assertResponseIsSuccessful();
    }

    public function testDestinationWithoutLoginUser(): void
    {
        $client = static::createClient();
        // Access login path
        $client->request('GET', '/admin/destination/new');
        $this->assertResponseRedirects('/login');

        $client->request('GET', '/admin/destination/');
        $this->assertResponseRedirects('/login');
    }

}
