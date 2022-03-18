<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AppHelp
 *
 * @ORM\Table(name="app_help")
 * @ORM\Entity
 */
class AppHelp
{
    /**
     * @var string
     *
     * @ORM\Column(name="top_level_name", type="string", length=255, nullable=true)
     */
    private $topLevelName;

    /**
     * @var string
     *
     * @ORM\Column(name="category_name", type="string", length=255, nullable=true)
     */
    private $categoryName;

    /**
     * @var string
     *
     * @ORM\Column(name="item_name", type="string", length=255, nullable=true)
     */
    private $itemName;

    /**
     * @var string
     *
     * @ORM\Column(name="item_content", type="text", nullable=true)
     */
    private $itemContent;

    /**
     * @var integer
     *
     * @ORM\Column(name="app_help_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $appHelpId;



    /**
     * Set topLevelName
     *
     * @param string $topLevelName
     *
     * @return AppHelp
     */
    public function setTopLevelName($topLevelName)
    {
        $this->topLevelName = $topLevelName;

        return $this;
    }

    /**
     * Get topLevelName
     *
     * @return string
     */
    public function getTopLevelName()
    {
        return $this->topLevelName;
    }

    /**
     * Set categoryName
     *
     * @param string $categoryName
     *
     * @return AppHelp
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    /**
     * Get categoryName
     *
     * @return string
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * Set itemName
     *
     * @param string $itemName
     *
     * @return AppHelp
     */
    public function setItemName($itemName)
    {
        $this->itemName = $itemName;

        return $this;
    }

    /**
     * Get itemName
     *
     * @return string
     */
    public function getItemName()
    {
        return $this->itemName;
    }

    /**
     * Set itemContent
     *
     * @param string $itemContent
     *
     * @return AppHelp
     */
    public function setItemContent($itemContent)
    {
        $this->itemContent = $itemContent;

        return $this;
    }

    /**
     * Get itemContent
     *
     * @return string
     */
    public function getItemContent()
    {
        return $this->itemContent;
    }

    /**
     * Get appHelpId
     *
     * @return integer
     */
    public function getAppHelpId()
    {
        return $this->appHelpId;
    }
}
