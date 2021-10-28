<?php

namespace Lpweb\SetefiSyliusPlugin\Payum;


class SetefiApi {

    /** @var string */
    private $id;

    /** @var string */
    private $password;

    /** @var boolean */
    private $sandbox;

    /**
     * @param string $id
     * @param string $password
     * @param bool   $sandbox
     */
    public function __construct(string $id, string $password, bool $sandbox) {
        $this->id = $id;
        $this->password = $password;
        $this->sandbox = $sandbox;
    }

    /**
     * @return string
     */
    public function getId(): string {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return SetefiApi
     */
    public function setId(string $id): SetefiApi {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return SetefiApi
     */
    public function setPassword(string $password): SetefiApi {
        $this->password = $password;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSandbox(): bool {
        return $this->sandbox;
    }

    /**
     * @param bool $sandbox
     *
     * @return SetefiApi
     */
    public function setSandbox(bool $sandbox): SetefiApi {
        $this->sandbox = $sandbox;

        return $this;
    }

}