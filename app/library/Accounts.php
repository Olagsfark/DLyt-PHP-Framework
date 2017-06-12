<?php

DStartScript();

/**
 * This class manages common account operations
 *
 * @author Olagoke Adedamola Farouq
 */
class Account{
    function __construct() {
        //
    }
    /**
     * this method can add new account with the follwing array of data
     * id => the account ID, much like a username.<br/>
     * first name => Account first name
     * surname => user surname
     * dob => date of birth
     * email => account email which is unique accross the system
     * phone => a valid phone number
     * country, state, city/town, acctLevel(Basic, Veteran, Premium)<br/><hr/>
     * not all values are required, but id,first&surname,email,phone,location, acctLevel
     *  are strictly required
     * @param array $accountData an array containing account data
     */
    public function create($accountData){
        //
    }
    public function delete($accountID){
        //
    }
    /**
     * responsible for editing account profile data
     * @param string $accountID the account ID/username
     * @param array $idOptions the fields you wana edit and the corresponding value
     */
    public function editProfile($accountID,$editData){
        //
    }
    public function exists($accountID){
        //
    }
    /**
     * this method tells if account is (suspended, dormant, closed, active)
     * @param array $idOptions
     */
    public function getStatus($accountID){
        //
    }
    /**
     * this method tells if account is (BASIC, VETERAN, PREMIUM)
     * @param array $idOptions
     */
    public function getType($accountID){
        //
    }
    /**
     * this method tells if account is (ADMIN, SUPER_ADMIN, USER)
     * @param array $idOptions
     */
    public function getPriviledge($accountID){
        //
    }
    /**
     * This method get all info on an account depending on the 
     * type of info (profile,device,activity,access) and returns them as an array
     * @param array $idOptions
     * @return array
     */
    public function getInfo($accountID, $infoType = ACCOUNTS_INFO_ALL){
        //
    }
}

define('ACCOUNTS_INFO_ALL',1);
define('ACCOUNTS_INFO_PROFILE',5);
define('ACCOUNTS_INFO_DEVICE',2);
define('ACCOUNTS_INFO_ACTIVITY',3);
define('ACCOUNTS_INFO_ACCESS',4);