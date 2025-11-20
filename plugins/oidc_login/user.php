<?php 

class OIDCIdentity extends DAO {
  public function __construct() {
    parent::__construct();
    $this->setTableName('t_oidc_identities');
    $this->setPrimaryKey(['s_provider', 's_sub']);
    $this->setFields([
      's_provider',
      's_sub',
      's_email',
      'fk_i_user_id',
      'dt_created_at',
      'dt_updated_at',
      's_access_token'
    ]);
  }

  public function findByProviderSub($provider, $sub) {
    if(empty($provider) || empty($sub)) {
      return null;
    }
    
    $this->dao->select();
    $this->dao->from($this->getTableName());
    $this->dao->where('s_provider', $provider);
    $this->dao->where('s_sub', $sub);

    $result = $this->dao->get();
    
    if($result != false && $result->numRows() == 1) {
      return $result->row();
    }
  }

  public function findOrCreateByUserInfo($provider, $userInfo) {
    if(empty($provider) || empty($userInfo) || empty($userInfo->sub)) {
      return null;
    }
    
    $sub = $userInfo->sub;
    $email = $userInfo->email;
    $identity = $this->findByProviderSub($provider, $sub);
    
    if(!$identity) {
      $this->dao->insert($this->getTableName(), [
          's_provider' => $provider,
          's_sub' => $sub,
          's_email' => $email,
          'dt_created_at' => date("Y-m-d H:i:s"),
        ]
      );
      $identity = $this->findByProviderSub($provider, $sub);
    }
    return $identity;
  }

  public function findOrCreateUserForIdentity($identity) {
    if(empty($identity)) {
      return null;
    }

    $userDAO = User::newInstance();
    
    // user is already linked
    if(!empty($identity['fk_i_user_id'])) {
      $user = $userDAO->findByPrimaryKey($identity['fk_i_user_id']);
    }

    // user exists but is not yet linked
    if(!$user && !empty($identity['s_email'])) {
      $user = $userDAO->findByEmail($identity['s_email']);
    }

    // user does not yet exist
    if(!$user && !empty($identity['s_email'])) {
      $now = date("Y-m-d H:i:s");
      $this->dao->insert($userDAO->getTableName(), [
        's_name' => $identity["s_sub"],
        's_email' => $identity['s_email'],
        's_secret' => osc_genRandomPassword(),
        's_password' => osc_hash_password(osc_genRandomPassword()),
        'b_enabled' => 1,
        'b_active' => 1,
        'dt_mod_date' => $now,
        'dt_reg_date' => $now
        ]
      );
      $userId = $this->dao->insertedId();
      $user = $userDAO->findByPrimaryKey($userId);

      if(osc_notify_new_user()) {
        osc_run_hook('hook_email_admin_new_user',$user);
      }

      osc_run_hook('user_register_completed', $userId);
    }

    return $user;
  }

  public function linkUserToIdentity($identity) {
    $user = $this->findOrCreateUserForIdentity($identity);

    if(empty($user)) {
      return null;
    }

    $userDAO = User::newInstance();
    $now = date("Y-m-d H:i:s");
    $userId = $user['pk_i_id'];

    $identityUpdateOk = $this->dao->update($this->getTableName(), ['fk_i_user_id' => $userId, 'dt_updated_at' => $now], 
                                              ['s_provider' => $identity['s_provider'], 's_sub' => $identity["s_sub"]]);

    $userUpdateOk = $this->dao->update($userDAO->getTableName(), ['dt_access_date' => $now, 'b_active' => 1], ['pk_i_id' => $userId]);
        
    if($identityUpdateOk && $userUpdateOk) {
      return $user;
    }
    return null;
  }
  
  public function findUserByUserInfo($provider, $userInfo) {
    $identity = $this->findOrCreateByUserInfo($provider, $userInfo);
    $user = $this->linkUserToIdentity($identity);

    if(empty($identity) || empty($user)) {
      return false;
    }

    return $user;
  }
}
