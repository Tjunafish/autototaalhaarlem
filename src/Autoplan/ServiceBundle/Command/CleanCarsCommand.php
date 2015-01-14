<?php
namespace Autoplan\ServiceBundle\Command;

use Autoplan\DBBundle\Entity\Car;
use Sensio\Bundle\GeneratorBundle\Command\GenerateBundleCommand;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\DomCrawler\Crawler;
use DOMElement;

use Symfony\Component\Console\Helper\DialogHelper;

use Doctrine\ORM\EntityManager;


class CleanCarsCommand extends ContainerAwareCommand
{

    private $output;

    protected function configure() {

        $this
            ->setName('clean:cars')
            ->setDescription('Cleans cars');
        ;

    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $this->output = $output;

        /** @var EntityManager $oEm  */
        $oEm = $this->getContainer()->get('doctrine')->getEntityManager();

        $output->writeln("------------------------------------------------------------------------------");
        $output->writeln("Cleaning cars");
        $output->writeln("------------------------------------------------------------------------------");

        $aCar = $oEm->getRepository('AutoplanDBBundle:Car')->findAll();

        /** @var Car $oCar */
        foreach($aCar as $oCar) {
            $output->writeln("Cleaning car");
            foreach($oCar->getPhotos() as $oObject) {
                $oEm->remove($oObject);
            }
            $oEm->remove($oCar);
        }
        $oEm->flush();

    }
}