<?php
/**
 * TitleToSlugTransformer.php file
 *
 * File that contains the title to slug transformer class
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Form/DataTransformer/TitleToSlugTransformer.php
 * @package    Mesd\HelpWikiBundle\Form\DataTransformer
 * @copyright  2014 (c) Multnomah Education Service District <http://www.mesd.k12.or.us>
 * @license    <http://opensource.org/licenses/MIT> MIT
 * @author     Curtis G Hanson <chanson@mesd.k12.or.us>
 * @version    {@inheritdoc}
 */
namespace Mesd\HelpWikiBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DomCrawler\Crawler;

use Mesd\HelpWikiBundle\Entity\Page;

class TitleToSlugTransformer implements DataTransformerInterface
{
    /**
     * Transforms a string (html) of headings to headings with permalinks (permalink), also a sting.
     *
     * @param  string $html
     *
     * @return string $html
     *
     * @throws TransformationFailedException if string ($html) is not found. (actually, it doesn't do anything)
     */
    public function transform($title)
    {
        return $title
    }

    /**
     * Transforms a string (html) of headings to headings with permalinks (permalink), also a sting.
     *
     * @param  Html|null $html
     * @return string
     */
    public function reverseTransform($slug)
    {
        return $slug;
    }

    public static function camelCase($str, $exclude = array())
    {
        // replace accents by equivalent non-accents
        $str = self::replaceAccents($str);
        // non-alpha and non-numeric characters become spaces
        $str = preg_replace('/[^a-z0-9' . implode("", $exclude) . ']+/i', ' ', $str);
        // uppercase the first character of each word
        $str = ucwords(trim($str));
        return str_replace(' ', '', $str);
    }
     
    public static function replaceAccents($str) {
        $search  = explode(',', "ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,ø,Ø,Å,Á,À,Â,Ä,È,É,Ê,Ë,Í,Î,Ï,Ì,Ò,Ó,Ô,Ö,Ú,Ù,Û,Ü,Ÿ,Ç,Æ,Œ");
        $replace = explode(',', "c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,o,O,A,A,A,A,A,E,E,E,E,I,I,I,I,O,O,O,O,U,U,U,U,Y,C,AE,OE");

        return str_replace($search, $replace, $str);
    }
}