<?php

namespace AppBundle\Entity;

/**
 * Orders
 */
class Orders
{
    /**
     * @var integer
     */
    private $carId;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $opinion;

    /**
     * @var integer
     */
    private $rate;

    /**
     * @var \DateTime
     */
    private $expDate;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set carId
     *
     * @param integer $carId
     *
     * @return Orders
     */
    public function setCarId($carId)
    {
        $this->carId = $carId;

        return $this;
    }

    /**
     * Get carId
     *
     * @return integer
     */
    public function getCarId()
    {
        return $this->carId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Orders
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Orders
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set opinion
     *
     * @param string $opinion
     *
     * @return Orders
     */
    public function setOpinion($opinion)
    {
        $this->opinion = $opinion;

        return $this;
    }

    /**
     * Get opinion
     *
     * @return string
     */
    public function getOpinion()
    {
        return $this->opinion;
    }

    /**
     * Set rate
     *
     * @param integer $rate
     *
     * @return Orders
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return integer
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set expDate
     *
     * @param \DateTime $expDate
     *
     * @return Orders
     */
    public function setExpDate($expDate)
    {
        $this->expDate = $expDate;

        return $this;
    }

    /**
     * Get expDate
     *
     * @return \DateTime
     */
    public function getExpDate()
    {
        return $this->expDate;
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
     * @var boolean
     */
    private $rateAccepted = '0';


    /**
     * Set rateAccepted
     *
     * @param boolean $rateAccepted
     *
     * @return Orders
     */
    public function setRateAccepted($rateAccepted)
    {
        $this->rateAccepted = $rateAccepted;

        return $this;
    }

    /**
     * Get rateAccepted
     *
     * @return boolean
     */
    public function getRateAccepted()
    {
        return $this->rateAccepted;
    }
}
