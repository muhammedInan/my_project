<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    private $path;

    private $file;

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath($path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file): void
    {
        $this->file = $file;

        
    }

    /**
     * @ORM\PreFlush()
     */
    public function handle()
    {
        if ($this->file === null){// si j'ai pas de soumis de fichier alors jai rien a fiare
            return;
        }
        if($this->id){ //image en edition
            unlink($this->path.'.'.$this->name);//recupere le fichier de limage et nom et le supprime

        }
         //recupere le file soumise lie a limageType de file
         //$file = $image->getFile(); sauf si il se trouve dans le controller
         $name = $this->createName();

         //met un nom aa limage
         $this->setName($name);
         //deplacer limage
         $this->file->move($this->path, $name);// se trouve dans le add controller son chemin 
    }

    private function createName() : string
    {
        ////cree un nom unique retourner un nom de fichier
        return md5(uniqid()). $this->file->getClientOriginalName(). '.'.$this->file->guessExtension();
    }
}
