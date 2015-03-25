TRUNCATE TABLE ip_views_old;
INSERT INTO ip_views_old SELECT * FROM ip_views;
TRUNCATE table ip_views;
TRUNCATE TABLE ip_clicks;
TRUNCATE TABLE impression_durations;
