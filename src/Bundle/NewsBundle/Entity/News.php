<?php

namespace Bundle\NewsBundle\Entity;

/**
 * @Entity
 * @Table(name="news")
 * @Entity(repositoryClass="Bundle\NewsBundle\Entity\NewsRepository")
 */

class News
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @Column(type="string", length="255")
     */
    protected $title;

    /**
     * @Column(type="text")
     */
    protected $content;


    /**
     * News creation date
     *
     * @Column(type="datetime")
     */
    protected $createdAt = null;


    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * getCreatedAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * getCreatedAtFormatted
     *
     * DateTime::format does not use locale informations ...
     * have to use strftime instead and to set the default timezone somewhere
     * (in the Bundle Kernel for exemple)
     *
     * @return string
     */
    public function getCreatedAtFormatted($format="%d %B %Y")
    {
        return strftime($format,$this->createdAt->getTimestamp());
    }

    /**
     * Set the repo creation date
     *
     * @return null
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

}