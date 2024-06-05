<?php

namespace App\Service;

use App\Entity\Seller;
use App\Repository\SellerRepository;
use Doctrine\ORM\EntityManagerInterface;

class SellerManager
{
 
    public function __construct(
        private EntityManagerInterface $entityManager, 
        private SellerRepository $sellerRepository
    )
    {
        
    }

    /**
     * Look for a seller by phone number and if there is not made new one
     */
    public function findOrCreateSeller($phone, $location): Seller
    {
        $seller = $this->sellerRepository->findOneBy(['phone' => $phone]);
        if (!$seller) {
            $seller = new Seller();
            $seller->setlocation($location);
            $seller->setPhone($phone);
            $this->entityManager->persist($seller);
            $this->entityManager->flush();
        }

        return $seller;
    }
    

}