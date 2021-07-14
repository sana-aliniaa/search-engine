<?php 
require( plugin_dir_path( __FILE__ ) . 'database.php');
require( plugin_dir_path( __FILE__ ) . 'display-result.php');
require( plugin_dir_path( __FILE__ ) . 'utils.php');

function search_profiles($name, $metier, $strategy = null) { 
    if ($strategy == null){
      $strategy = SearchStrategy::Or;
    }

    $db = new Database();
    $users = get_users();
    $nameResult = array();
    foreach($users as $user){
        if (strtolower($user->user_firstname) == strtolower($name) ){
          $nameResult[$user->id] = strtolower($user->user_firstname);
        }
    }
  
    // get metier result
    $sql = "SELECT user_id,value FROM wp_bp_xprofile_data where lower(value)=?"; 
    $rows = $db->query($sql, [strtolower($metier)]);
    $metierResult = array();
    foreach($rows as $row){
      $metierResult[$row['user_id']]=strtolower($row['value']);
    }

    $nameOrMetier = array_unique(array_merge(array_keys($nameResult), array_keys($metierResult)));
    $nameAndMetier = array_intersect(array_keys($nameResult), array_keys($metierResult));

    if ($strategy == SearchStrategy::And){
      displayUsers($nameAndMetier);
    }
    else if ($strategy == SearchStrategy::Or){
      displayUsers($nameOrMetier);
    }
}

?>