<?php

if (!$Viewer->permitted('site_view_flow')) {
    error(403);
}

echo $Twig->render('admin/stats/torrent.twig', [
    'torr_stat' => new Gazelle\Stats\Torrent,
    'user_stat' => new Gazelle\Stats\Users,
]);
