<?php
/**
 * EntitiesToStringTransformer.php file
 *
 * File that contains the title to slug transformer class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Form/DataTransformer/EntitiesToStringTransformer.php
 * @package    Mesd\HelpWikiBundle\Form\DataTransformer
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    0.1.0
 */
namespace Mesd\HelpWikiBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\TransformationFailedException;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

use Mesd\HelpWikiBundle\Entity\Tag;

class EntitiesToStringTransformer implements DataTransformerInterface
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * Constructor injection. Set entity manager to object
     *
     * @param Doctrine\ORM\EntityManager $em Entity manager object
     *
     * @return void
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Transforms a string of tags to tag objects.
     *
     * @param  Collection|null $collection A collection of entities or NULL
     *
     * @throws UnexpectedTypeException If not collection.
     * @return string|null             An string of tags or NULL
     */
    public function transform($collection)
    {
        if (null === $collection) {
            return null;
        }

        if (!($collection instanceof Collection)) {
            throw new UnexpectedTypeException($collection, 'Doctrine\Common\Collections\Collection');
        }

        $array = array();

        foreach ($collection as $entity) {
            array_push($array, $entity->getName());
        }

        return implode(',', $array);
    }

    /**
     * Transforms a string (html) of headings to headings with permalinks (permalink), also a sting.
     *
     * @param  Html|null $html
     * @return string
     */
    public function reverseTransform($tags)
    {
        $collection = new ArrayCollection();

        if ('' === $tags || null === $tags) {
            return $collection;
        }

        if (!is_string($tags)) {
            throw new UnexpectedTypeException($tags, 'string');
        }

        foreach ($this->stringToArray($tags) as $name) {
            $tag = $this->em->getRepository("MesdHelpWikiBundle:Tag")->findOneBy(array('name' => $name));
            
            if (!$tag) {
                $tag = new Tag();
                $tag->setName($name);
                $this->em->persist($tag);
            }

            $collection->add($tag);
        }

        /**
         * @todo still need to add management of tags that have been removed
         *       and are no longer tied to any other page entities
         */

        return $collection;
    }

    /**
     * Convert string of tags to array
     *
     * @param string $string
     */
    private function stringToArray($string)
    {
        $tags = explode(',', $string);

        // strip whitespaces from beginning and end of a tag name
        foreach ($tags as &$name) {
            $name = trim($name);
        }

        // remove duplicates
        return array_unique($tags);
    }

}