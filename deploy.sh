#!/bin/bash

# ============================================
# STTP Deployment Script untuk Linux/Unix
# ============================================
# Usage: bash deploy.sh

set -e

echo "======================================"
echo "STTP Deployment Script"
echo "======================================"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: artisan file not found. Run this script from project root.${NC}"
    exit 1
fi

echo -e "${YELLOW}Step 1: Installing PHP dependencies...${NC}"
composer install --optimize-autoloader --no-dev

echo -e "${YELLOW}Step 2: Installing Node dependencies...${NC}"
npm install --production

echo -e "${YELLOW}Step 3: Building Vite assets...${NC}"
npm run build

echo -e "${YELLOW}Step 4: Setting permissions...${NC}"
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Check if running as different user (like www-data)
if [ "$EUID" -eq 0 ]; then
    echo -e "${YELLOW}Step 5: Setting file ownership to www-data...${NC}"
    chown -R www-data:www-data storage
    chown -R www-data:www-data bootstrap/cache
    chown -R www-data:www-data public/storage
fi

echo -e "${YELLOW}Step 6: Running database migrations...${NC}"
php artisan migrate --force

echo -e "${YELLOW}Step 7: Clearing cache...${NC}"
php artisan cache:clear
php artisan config:clear
php artisan view:clear

echo -e "${YELLOW}Step 8: Generating key (if needed)...${NC}"
php artisan key:generate 2>/dev/null || true

echo -e "${YELLOW}Step 9: Publishing assets...${NC}"
php artisan storage:link 2>/dev/null || true

echo -e "${GREEN}======================================"
echo "✓ Deployment completed successfully!"
echo "=====================================${NC}"
echo ""
echo "Next steps:"
echo "1. Verify .env configuration"
echo "2. Test your application"
echo "3. Setup SSL/TLS certificate"
echo "4. Configure cron jobs (optional)"
echo ""
