<?php

namespace Mesd\HelpWikiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Media
 */
class Media
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $caption;

    /**
     * @var string
     */
    private $alt;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $filepath;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $mimetype;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var \DateTime
     */
    private $dateTime;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $pages;

    /**
     * @var \Mesd\Orcase\UserBundle\Entity\User
     */
    private $user;

    /**
     * A file
     * 
     * @var Symfony\Component\HttpFoundation\File\UploadedFile
     */
    private $file;

    private $temp;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Media
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set caption
     *
     * @param string $caption
     * @return Media
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    
        return $this;
    }

    /**
     * Get caption
     *
     * @return string 
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set alt
     *
     * @param string $alt
     * @return Media
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
    
        return $this;
    }

    /**
     * Get alt
     *
     * @return string 
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Media
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set filepath
     *
     * @param string $filepath
     * @return Media
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;
    
        return $this;
    }

    /**
     * Get filepath
     *
     * @return string 
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * Set filename
     *
     * @param string $filename
     * @return Media
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    
        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set mimetype
     *
     * @param string $mimetype
     * @return Media
     */
    public function setMimetype($mimetype)
    {
        $this->mimetype = $mimetype;
    
        return $this;
    }

    /**
     * Get mimetype
     *
     * @return string 
     */
    public function getMimetype()
    {
        return $this->mimetype;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Media
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    
        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set dateTime
     *
     * @param \DateTime $dateTime
     * @return Media
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
    
        return $this;
    }

    /**
     * Get dateTime
     *
     * @return \DateTime 
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * Add pages
     *
     * @param \Mesd\HelpWikiBundle\Entity\Page $pages
     * @return Media
     */
    public function addPage(\Mesd\HelpWikiBundle\Entity\Page $pages)
    {
        $this->pages[] = $pages;
    
        return $this;
    }

    /**
     * Remove pages
     *
     * @param \Mesd\HelpWikiBundle\Entity\Page $pages
     */
    public function removePage(\Mesd\HelpWikiBundle\Entity\Page $pages)
    {
        $this->pages->removeElement($pages);
    }

    /**
     * Get pages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Set user
     *
     * @param \Mesd\Orcase\UserBundle\Entity\User $user
     * @return Media
     */
    public function setUser(\Mesd\Orcase\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Mesd\Orcase\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Generate hash
     *
     * @param string  $hash
     * @return DocumentedEntity
     */
    public function generateHash($data)
    {
        $this->hash = hash('sha512', $data);
        return $this;
    }
    /**
     * Generate hash
     *
     * @param string  $filename
     * @return DocumentedEntity
     */
    public function generateHashFromFile($file)
    {
        $this->hash = hash_file('sha512', $file);
        return $this;
    }


    public function getAbsolutePath()
    {
        return null === $this->filepath
            ? null
            : $this->getUploadRootDir()
            . '/' . $this->getFilepath()
            . '/' . $this->getFilename()
            . '.' . $this->getMimetype();
    }

    public function getWebPath()
    {
        return null === $this->filepath
            ? null
            : $this->getUploadDir()
            . '/' . $this->getFilepath()
            . '/' . $this->getFilename()
            . '.' . $this->getMimetype();
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/mesdhelpwiki';
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        if('array' == gettype($file)){
            // if there are multiple files uploaded
            foreach($file as $single){
                $this->file = $single;

                // check if we have an old image path
                if (is_file($this->getAbsolutePath())) {

                    // store the old name to delete after the update
                    $this->temp = $this->getAbsolutePath();
                }
                else {
                    $this->path = 'initial';
                }
            }
        }
        else{
            // if there is only one file
            $this->file = $file;

            // check if we have an old image path
            if (is_file($this->getAbsolutePath())) {

                // store the old name to delete after the update
                $this->temp = $this->getAbsolutePath();
            }
            else {
                $this->path = 'initial';
            }
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * PreUpload Action
     *
     * On lifecycle callbacks:
     *
     *     * prePersist
     *     * preUpdate
     *  
     * @return void
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $this->generateHashFromFile($this->getFile());
            $this->mimetype = $this->getFile()->guessExtension();
            $this->dateTime = new \DateTime();
            $this->filepath = $this->dateTime->format('Y') . '/' . $this->dateTime->format('m') . '/';
            $this->filename = $this->sanitizeFilename(pathinfo($this->getFile()->getClientOriginalName(), PATHINFO_FILENAME));
            $this->mimetype = $this->getFile()->guessExtension();
            ;
        }
    }

    /**
     * Upload Action
     *
     * On lifecycle callbacks:
     *
     *     * postPersist
     *     * postUpdate
     *  
     * @return void
     */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // check if we have an old image
        if (isset($this->temp)) {

            // delete the old image
            unlink($this->temp);

            // clear the temp image path
            $this->temp = null;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues
        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does
        $this->getFile()->move(
            $this->getUploadRootDir() . '/' . $this->getFilepath(),
            $this->getFilename() . '.' . $this->getMimetype()
        );

        // clean up the file property as you won't need it anymore
        $this->setFile(null);
    }

    /**
     * Store Filename for Remove
     * 
     * On lifecycle callbacks:
     *
     *     * preRemove
     */
    public function storeFilenameForRemove()
    {
        $this->temp = $this->getAbsolutePath();
    }

    /**
     * Remove Upload
     * 
     * On lifecycle callbacks:
     *
     *     * postRemove
     */
    public function removeUpload()
    {
        if (isset($this->temp)) {
            unlink($this->temp);
        }
    }

    /**
     * Default __toString.
     */
    public function __toString() {
        return $this->getFilename();
    }

    function sanitizeFilename($filename) {
        // a combination of various methods
        // we don't want to convert html entities, or do any url encoding
        // we want to retain the "essence" of the original file name, if possible
        // char replace table found at:
        // http://www.php.net/manual/en/function.strtr.php#98669
        $replace_chars = array(
            'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
            'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
            'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
            'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
            'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
            'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
            'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f'
        );
        $filename = strtr($filename, $replace_chars);
        // convert & to "and", @ to "at", and # to "number"
        $filename = preg_replace(array('/[\&]/', '/[\@]/', '/[\#]/'), array('-and-', '-at-', '-number-'), $filename);

        // removes any special chars we missed
        $filename = preg_replace('/[^(\x20-\x7F)]*/','', $filename);

        // convert space to hyphen 
        $filename = str_replace(' ', '-', $filename);

        // removes apostrophes
        $filename = str_replace('\'', '', $filename);

        // remove non-word chars (leaving hyphens and periods)
        $filename = preg_replace('/[^\w\-\.]+/', '', $filename);

        // converts groups of hyphens into one
        $filename = preg_replace('/[\-]+/', '-', $filename);

        return strtolower($filename);
    }
}
