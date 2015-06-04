<?php
/**
 * HeadingToPermalinkTransformer.php file
 *
 * File that contains the heading to permalink transformer file
 *
 * Licence MIT
 * Copyright (c) 2014 Multnomah Education Service District <http://www.mesd.k12.or.us>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * @filesource /src/Mesd/HelpWikiBundle/Form/DataTransformer/HeadingToPermalinkTransformer.php
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

class HeadingToPermalinkTransformer implements DataTransformerInterface
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
    public function transform($page, $slug = null)
    {
        // data from database
        // don't do anything. i don't give a shit
        // return $html;

        $html = '';
        $slug = empty($slug) ? '' : self::camelCase($slug);

        // currently, we only care about headings
        $headings = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];

        // data to database
        if (null === $page) {
            return '';
        }

        $dom = new \DomDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadHTML($page, LIBXML_HTML_NODEFDTD | LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);

        $nodes = $dom->getElementsByTagName('*');
        if ($nodes instanceof \DOMNodeList) {
            $i = 0;
            foreach ($nodes as $node) {
                // if there's a header, lets give it a title
                if (in_array($node->tagName, $headings)) {
                    // to ensure a unique but non-random heading id,
                    // we camelCase the slug name, iteration, tag name, and tag inner html
                    $title = $slug . '-' . $node->tagName . '-' . $i . '-' . self::camelCase($node->nodeValue);
                    $node->setAttribute('id', $title);
                    $i++;
                }
            }

            // save to the dom
            $html .= $dom->saveHTML();
        }

        return $html;
    }

    /**
     * Transforms a string (html) of headings to headings with permalinks (permalink), also a sting.
     *
     * @param  Html|null $html
     * @return string
     */
    public function reverseTransform($html)
    {
        // data to database
        if (null === $html) {
            return "";
        }

        $crawler = new Crawler($html);

        $crawler = $crawler->filterXPath('//h1 | //h2 | //h3 | //h4 | //h5 | //h6');

        return $html;
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