<?php
$mysqli = new mysqli("localhost", "malik", "ma123lik456", "erp");
//$mysqli = new mysqli("localhost", "user", "123", "erp");
//$mysqli = new mysqli("127.0.0.1", "erp_user", "qNStq6NeZQvfAVHF", "erp");
if ($mysqli->connect_errno) {
    echo "Echec lors de la connexion à MySQL : (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$arrayCalendar = array();

/* <----- On récupère le congés */
$sql = '
	SELECT 
		d.iddata,
        d.title,
		dv1.value as data_start,
        dv2.value as date_end,
        dv3.value_str as people,
        dv4.value as description
	FROM 
		datas d
	INNER JOIN datas_values dv1 ON (dv1.iddata = d.iddata AND dv1.iddata_field = 50)
    INNER JOIN datas_values dv2 ON (dv2.iddata = d.iddata AND dv2.iddata_field = 51)
    INNER JOIN datas_values dv3 ON (dv3.iddata = d.iddata AND dv3.iddata_field = 52)
    INNER JOIN datas_values dv4 ON (dv4.iddata = d.iddata AND dv4.iddata_field = 53)
	WHERE 
		d.iddata_type = 7
		AND
		d.iddata_status = 25
		AND
		d.iduser_delete is null
        AND
        ((dv1.value > \''.$_POST['start'].'\'
		AND
		dv1.value < \''.$_POST['end'].'\')
		OR
		(dv2.value > \''.$_POST['start'].'\'
		AND
		dv2.value < \''.$_POST['end'].'\'))';

if($result = $mysqli->query($sql)){
    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $endDate = new DateTime($row['date_end']);
        $endDate = $endDate->modify('+1 day')->format('Y-m-d');
        $arrayCalendar[] = array(
            'title' => utf8_encode($row['people']),
            'start' => $row['data_start'],
            'end' => $endDate,
            'className' => 'conge',
            'allDay' => true,
            'description' => utf8_encode($row['description'])
        );
    }
}
$result->free();
/* Fin congé -----> */

/* <----- On récupère le télétravail */
$sql = '
	SELECT 
		d.iddata,
        d.title,
		dv1.value as data_start,
        dv2.value as date_end,
        dv3.value_str as people
	FROM 
		datas d
	INNER JOIN datas_values dv1 ON (dv1.iddata = d.iddata AND dv1.iddata_field = 1041)
    INNER JOIN datas_values dv2 ON (dv2.iddata = d.iddata AND dv2.iddata_field = 1051)
    INNER JOIN datas_values dv3 ON (dv3.iddata = d.iddata AND dv3.iddata_field = 1061)
	WHERE 
		d.iddata_type = 71
		AND
		d.iddata_status = 411
		AND
		d.iduser_delete is null
        AND
        ((dv1.value > \''.$_POST['start'].'\'
		AND
		dv1.value < \''.$_POST['end'].'\')
		OR
		(dv2.value > \''.$_POST['start'].'\'
		AND
		dv2.value < \''.$_POST['end'].'\'))';

if($result = $mysqli->query($sql)){
    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $endDate = new DateTime($row['date_end']);
        $endDate = $endDate->modify('+1 day')->format('Y-m-d');
        $arrayCalendar[] = array(
            'title' => utf8_encode($row['people']),
            'start' => $row['data_start'],
            'end' => $endDate,
            'className' => 'teletravail',
            'allDay' => true
        );
    }
}
$result->free();
/* Fin Télétravail -----> */

/* <----- On récupère les tickets deadline */
$sql = '
	SELECT 
		d.iddata,
        d.title,
		d.uid,
		dv1.value as date_deadline
	FROM 
		datas d
	INNER JOIN datas_values dv1 ON (dv1.iddata = d.iddata AND dv1.iddata_field = 1081)
	WHERE 
		d.iddata_type = 1
		AND
		d.iduser_delete is null
        AND
        dv1.value > \''.$_POST['start'].'\'
		AND
		dv1.value < \''.$_POST['end'].'\'';

if($result = $mysqli->query($sql)){
    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $arrayCalendar[] = array(
            'title' => utf8_encode('#'.$row['iddata'].' - '.$row['title']),
            'start' => $row['date_deadline'],
			'link' => 'https://erp.yoomap.fr/data/ticket/consult/'.$row['uid'],
            'className' => 'ticket',
            'allDay' => true
        );
    }
}
$result->free();
/* Fin Télétravail -----> */

/* Fermeture de la connexion */
$mysqli->close();
echo json_encode($arrayCalendar, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
?>
