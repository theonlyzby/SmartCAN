cd /data/sys-files/zigbee2mqtt

# Backup configuration
cp -R data data-backup

# Update
git checkout HEAD -- npm-shrinkwrap.json
git fetch
git checkout dev # Change 'dev' to 'master' to switch back to the release version
git pull
npm ci

# Restore configuration
cp -R data-backup/* data
rm -rf data-backup