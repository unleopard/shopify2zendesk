<?php

/**
 * Description of Entity
 *
 * @Created by Majoch abdessamad.
 * @Date: 19/05/2020
 * @Mail: majoch.abdessamad@gmail.com
 * @Autor: majoch abdessamad
 * @File: user
 */

class user extends Entities
{

    private $id_user;
    private $type_compte;
    private $username;
    private $password;
    private $nom;
    private $prenom;
    private $adresse;
    private $ville;
    private $telephone;
    private $mail;
    private $profession;
    private $date_ajout;
    private $date_quite;
    private $actif;

    function __construct($id_user = null, $type_compte = null, $username = null, $password = null, $nom = null, $prenom = null, $adresse = null, $ville = null, $telephone = null, $mail = null, $profession = null, $date_ajout = null, $date_quite = null, $actif = null)
    {
        $this->id_user = $id_user;
        $this->type_compte = $type_compte;
        $this->username = $username;
        $this->password = $password;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->adresse = $adresse;
        $this->ville = $ville;
        $this->telephone = $telephone;
        $this->mail = $mail;
        $this->profession = $profession;
        $this->date_ajout = $date_ajout;
        $this->date_quite = $date_quite;
        $this->actif = $actif;

        $this->table = "users";
        $this->primaryKey = "id_user";
        $this->foreignKey = array(
            'type_compte' => typeCompte::class
        );

        $this->metadata = array(
            'id_user' => 'id_user',
            'type_compte' => 'type_compte',
            'username' => 'username',
            'password' => 'password',
            'nom' => 'nom',
            'prenom' => 'prenom',
            'adresse' => 'adresse',
            'ville' => 'ville',
            'telephone' => 'telephone',
            'mail' => 'mail',
            'profession' => 'profession',
            'date_ajout' => 'date_ajout',
            'date_quite' => 'date_quite',
            'actif' => 'actif'
        );
    }

    public function getIdUser()
    {
        return $this->id_user;
    }

    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }

    public function getTypeCompte()
    {
        return $this->type_compte;
    }

    public function setTypeCompte($type_compte)
    {
        $this->type_compte = $type_compte;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    public function getAdresse()
    {
        return $this->adresse;
    }

    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    public function getVille()
    {
        return $this->ville;
    }

    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    public function getMail()
    {
        return $this->mail;
    }

    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    public function getProfession()
    {
        return $this->profession;
    }

    public function setProfession($profession)
    {
        $this->profession = $profession;
    }

    public function getDateAjout()
    {
        return $this->date_ajout;
    }

    public function setDateAjout($date_ajout)
    {
        $this->date_ajout = $date_ajout;
    }

    public function getDateQuite()
    {
        return $this->date_quite;
    }

    public function setDateQuite($date_quite)
    {
        $this->date_quite = $date_quite;
    }

    public function getActif()
    {
        return $this->actif;
    }

    public function setActif($actif)
    {
        $this->actif = $actif;
    }


    public function tableShow()
    {
        return "";
    }


    public function to_json()
    {
        return array(
            'id' => $this->id_user,
            'typeComp' => $this->type_compte->getId(),
            'username' => $this->username,
            'password' => $this->password,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'adresse' => $this->adresse,
            'ville' => $this->ville->getId(),
            'telephone' => $this->telephone,
            'mail' => $this->mail,
            'date_ajout' => $this->date_ajout,
            'date_quite' => $this->date_quite,
            'actif' => $this->actif
        );
    }
}