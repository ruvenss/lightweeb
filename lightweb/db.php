<?php
/**
 * DO NOT INSERT YOUR CODE HERE! THIS FILE WILL BE REWRITE IN THE NEXT UPDATE
 * USE ONLY FILES THAT BEGIN BY my_
 * 
 * @author Ruvenss G. Wilches <ruvenss@gmail.com>
 */
function sqlSanitize($value)
{
    if (isset($value)) {
        $search = array("\\", "\x00", "\n", "\r", "'", '"', "\x1a");
        $replace = array("\\\\", "\\0", "\\n", "\\r", "\'", '\"', "\\Z");
        return str_replace($search, $replace, $value);
    } else {
        return null;
    }
}
function sqlTableIndex($table)
{
    $sqlquery = "SHOW INDEX FROM `" . $table . "`";
    $result = ldb->query($sqlquery);
    while ($row = $result->fetch_assoc()) {
        return ($row['Column_name']);
    }
}
function sqlTableFields($table)
{
    $sqlquery = "SHOW COLUMNS FROM `" . $table . "`";
    $result = ldb->query($sqlquery);
    $arrFields = array();
    while ($row = $result->fetch_assoc()) {
        array_push($arrFields, $row['Field']);
    }
    return ($arrFields);
}
function sqlInsert($table, $fields = array(), $values = array(), $userid = 1, $logged = false)
{
    if (strlen($table) > 0 && sizeof($fields) > 0 && sizeof($values) == sizeof($fields) && $userid > 0) {
        $querylog = array();
        $sqlquery = "INSERT INTO $table(`" . implode("`,`", $fields) . "`) VALUES(";
        for ($i = 0; $i < sizeof($values); $i++) {
            if ($values[$i] == "NOW()") {
                $values[$i] = "NOW()";
            } else {
                $values[$i] = '"' . mysqli_real_escape_string(ldb, $values[$i]) . '"';
            }

        }
        $sqlquery .= implode(",", $values);
        $sqlquery .= ")";
        //echo $sqlquery . "\n";
        try {
            mysqli_query(ldb, $sqlquery);
            return (mysqli_insert_id(ldb));
        } catch (Exception $e) {
            return (null);
        }
    } else {
        echo ("\r\n<br>sqlInsert parameters incorrect: size of fileds=" . sizeof($fields) . " size of values=" . sizeof($values));
    }
}
function sqlInsertUpdate($table, $fields = [], $values = [], $onupdate = "", $logged = false)
{
    $insertFileds = '`' . implode("`,`", $fields) . '`';
    for ($i = 0; $i < sizeof($values); $i++) {
        if ($values[$i] == "NOW()") {
            $values[$i] = "NOW()";
        } else {
            $values[$i] = '"' . mysqli_real_escape_string(ldb, $values[$i]) . '"';
        }

    }
    $insertValues = implode(",", $values);
    $sqlquery = "INSERT INTO `$table`($insertFileds) VALUES ($insertValues) ON DUPLICATE KEY UPDATE $onupdate;";
    try {
        mysqli_query(ldb, $sqlquery);
        return (mysqli_insert_id(ldb));
    } catch (Exception $e) {
        error_log($sqlquery, 0);
        return (null);
    }
}
function sqlSelect($table, $field, $where, $orderby = "", $limit = "")
{
    if (strlen($table) > 0 && strlen($field) > 0) {
        $sqlquery = "SELECT `$field` FROM `$table`";
        if (strlen($where) > 0) {
            $sqlquery .= " WHERE ($where)";
        }
        if (strlen($orderby) > 0) {
            $sqlquery .= " ORDER BY `$orderby`";
        }
        if (strlen($limit) > 0) {
            $sqlquery .= " LIMIT $limit";
        }
        $result = ldb->query($sqlquery);
        if (!$result) {
            return (false);
        } else {
            while ($row = $result->fetch_assoc()) {
                return $row[$field];
            }
        }
    }
}
function sqlUpdate($table, $fields = [], $values = [], $keyfield = "", $keyvalue = "", $userid = 1)
{
    if (strlen($table) > 0 && sizeof($fields) > 0 && sizeof($values) == sizeof($fields) && $userid > 0) {
        $sqlquery = "UPDATE `$table` SET ";
        $v = "";
        for ($i = 0; $i < sizeof($values); $i++) {
            if ($values[$i] == "NOW()") {
                if ($i > 0) {
                    $v = ",";
                }
                $sqlquery .= $v . $fields[$i] . '=NOW()';
            } else {
                if (strlen($values[$i]) > 0) {
                    $values[$i] = '"' . mysqli_real_escape_string(ldb, $values[$i]) . '"';
                } else {
                    $values[$i] = "NULL";
                }
                if ($i > 0) {
                    $v = ",";
                }
                $sqlquery .= $v . $fields[$i] . '=' . $values[$i] . '';
            }
        }
        $sqlquery .= ' WHERE `' . $keyfield . '`="' . $keyvalue . '"';
        //error_log($sqlquery, 0);
        mysqli_query(ldb, $sqlquery);
        return (true);
    }
}
function sqlSelectRow($table, $fields, $where, $orderby = "")
{
    if (strlen($table) > 0 && strlen($fields) > 0) {
        $sqlquery = "SELECT $fields FROM `$table`";
        if (strlen($where) > 0) {
            $sqlquery .= " WHERE ($where)";
        }
        if (strlen($orderby) > 0) {
            $sqlquery .= " ORDER BY `$orderby`";
        }
        $sqlquery .= " LIMIT 1";
        $result = ldb->query($sqlquery);
        if (!$result) {
            //echo $sqlquery;
            error_log("sqlSelect empty result : $sqlquery", 0);
        } else {
            while ($row = $result->fetch_assoc()) {
                return $row;
            }
        }
    }
}
function sqlSelectRows($table, $fields, $where, $orderby = "", $limit = "")
{
    if (strlen($table) > 0 && strlen($fields) > 0) {
        $sqlquery = "SELECT $fields FROM `$table`";
        if (strlen($where) > 0) {
            $sqlquery .= " WHERE ($where)";
        }
        if (strlen($orderby) > 0) {
            $sqlquery .= " ORDER BY $orderby";
        }
        if (strlen($limit) > 0) {
            $sqlquery .= " LIMIT $limit";
        }
        $result = ldb->query($sqlquery);
        if (!$result) {
            return (null);
        } else {
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                array_push($rows, $row);
            }
            return ($rows);
        }
    }
}
function sqlCount($table, $where)
{
    if (strlen($table) > 0 && strlen($where) > 0) {
        $sqlquery = "SELECT count(*) AS TOTAL FROM $table WHERE $where";
        $result = ldb->query($sqlquery);
        if (!$result) {
            return 0;
        }
        while ($row = $result->fetch_assoc()) {
            return $row['TOTAL'];
        }
    }
}
function sqlSum($table, $field, $where)
{
    if (strlen($table) > 0 && strlen($where) > 0) {
        $sqlquery = "SELECT sum($field) AS TOTAL FROM $table WHERE $where";
        //echo "SQL sqlCount:".$sqlquery;
        //return $sqlquery;
        //error_log("sqlSum query:".$sqlquery,0);
        $result = ldb->query($sqlquery);
        if (!$result) {
            return 0;
        }
        while ($row = $result->fetch_assoc()) {
            return $row['TOTAL'];
        }
    }
}
function sqlTableInformationOf($table)
{
    $fields = array();
    $result = ldb->query("DESC `$table`");
    while ($field = $result->fetch_assoc()) {
        array_push($fields, $field['Field']);
    }
    return $fields;
}
function DuplicateMySQLRecord($table, $id_field, $id)
{
    // load the original record into an array
    $result = ldb->query("SELECT * FROM {$table} WHERE {$id_field}={$id}");
    $original_record = $result->fetch_assoc();
    // insert the new record and get the new auto_increment id
    mysqli_query(ldb, "INSERT INTO {$table} (`{$id_field}`) VALUES (NULL)");
    $newid = mysqli_insert_id(ldb);
    // generate the query to update the new record with the previous values
    $query = "UPDATE {$table} SET ";
    foreach ($original_record as $key => $value) {
        if ($key != $id_field) {
            $query .= '`' . $key . '` = "' . str_replace('"', '\"', $value) . '", ';
        }
    }
    $query = substr($query, 0, strlen($query) - 2); # lop off the extra trailing comma
    $query .= " WHERE {$id_field}={$newid}";
    mysqli_query(ldb, $query);
    // return the new id
    error_log("DuplicateMySQLRecord query:" . $query, 0);
    return $newid;
}
function sqlTableExist($table, $dbname)
{
    $query = "SELECT COUNT(TABLE_NAME) AS `EXIST` FROM information_schema.TABLES WHERE table_schema = '$dbname' AND TABLE_NAME = '$table';";
    $apidb = ldb->query($query);
    while (($row = $apidb->fetch_assoc()) !== false) {
        if ($row['EXIST'] == "0" || $row['EXIST'] == "false") {
            return (false);
        } else {
            return (true);
        }
    }
    return (false);
}