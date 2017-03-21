<?php
$mysqli = new mysqli("localhost", "malik", "ma123lik456", "erp");
if ($mysqli->connect_errno) {
    echo "Echec lors de la connexion à MySQL : (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

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
        dv2.value > NOW()';

$arrayCalendar = array();
if($result = $mysqli->query($sql)){
    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $arrayCalendar[] = array(
            'title' => $row['people'],
            'start' => $row['data_start'],
            'end' => $row['date_end'],
            'color' => '#A7D2C8',
            'allDay' => true,
            'description' => utf8_encode($row['description'])
        );
    }
}

$result->free();
/* Fin congé -----> */


/* Fermeture de la connexion */
$mysqli->close();

echo json_encode($arrayCalendar, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
?>
