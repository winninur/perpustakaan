#!/usr/bin/env bash
# =============================================================================
# SETUP SCRIPT - Sistem Informasi Perpustakaan (Laravel 12)
# Kelompok AF - Pemrograman Web Tingkat Mahir
# =============================================================================
# Cara pakai:
#   1. chmod +x setup.sh
#   2. ./setup.sh
#
# Script ini akan otomatis:
#   - Install dependency PHP (composer install)
#   - Copy .env.example ke .env
#   - Generate APP_KEY
#   - Buat folder storage yang dibutuhkan
#   - Migrate database + seed
#   - Jalankan server
# =============================================================================

set -e

# Warna untuk output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}================================================${NC}"
echo -e "${BLUE}  SETUP - Sistem Informasi Perpustakaan${NC}"
echo -e "${BLUE}================================================${NC}"
echo ""

# Step 0: Cek composer
echo -e "${YELLOW}[0/8] Cek composer...${NC}"
if ! command -v composer &> /dev/null; then
    echo -e "${RED}ERROR: composer tidak ditemukan!${NC}"
    echo "Install composer dulu: https://getcomposer.org/download/"
    exit 1
fi
echo -e "${GREEN}OK - composer ditemukan${NC}"
echo ""

# Step 1: composer install
echo -e "${YELLOW}[1/8] Install dependency PHP (composer install)...${NC}"
composer install --no-interaction --prefer-dist
echo -e "${GREEN}Done${NC}"
echo ""

# Step 2: Copy .env
echo -e "${YELLOW}[2/8] Setup file .env...${NC}"
if [ ! -f .env ]; then
    cp .env.example .env
    echo -e "${GREEN}File .env dibuat dari .env.example${NC}"
else
    echo -e "${YELLOW}File .env sudah ada, lewati${NC}"
fi
echo ""

# Step 3: Generate APP_KEY
echo -e "${YELLOW}[3/8] Generate APP_KEY...${NC}"
php artisan key:generate --force
echo -e "${GREEN}Done${NC}"
echo ""

# Step 4: Pastikan folder storage ada
echo -e "${YELLOW}[4/8] Setup folder storage...${NC}"
mkdir -p storage/app/public
mkdir -p storage/app/private
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
chmod -R 775 storage bootstrap/cache
echo -e "${GREEN}Done${NC}"
echo ""

# Step 5: Tes koneksi database
echo -e "${YELLOW}[5/8] Tes koneksi database...${NC}"
echo ""
echo -e "${BLUE}Pastikan database sudah dibuat di phpMyAdmin:${NC}"
echo -e "  Nama database : ${YELLOW}db_perpustakaan${NC}"
echo -e "  Username      : ${YELLOW}root${NC}"
echo -e "  Password      : ${YELLOW}(kosong untuk XAMPP/Laragon)${NC}"
echo ""
echo "Cek file .env untuk konfigurasi, lalu tekan ENTER untuk lanjut..."
read -r

# Step 6: Migrate database
echo -e "${YELLOW}[6/8] Migrate database + seed...${NC}"
php artisan migrate:fresh --seed --force
echo -e "${GREEN}Done${NC}"
echo ""

# Step 7: Clear cache
echo -e "${YELLOW}[7/8] Clear cache config...${NC}"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo -e "${GREEN}Done${NC}"
echo ""

# Step 8: Info login
echo -e "${YELLOW}[8/8] Setup selesai!${NC}"
echo ""
echo -e "${BLUE}================================================${NC}"
echo -e "${GREEN}  AKUN LOGIN DEFAULT${NC}"
echo -e "${BLUE}================================================${NC}"
echo -e "  Admin      : ${YELLOW}admin@perpus.id${NC}      / ${YELLOW}admin123${NC}"
echo -e "  Pustakawan : ${YELLOW}pustakawan@perpus.id${NC} / ${YELLOW}pustakawan123${NC}"
echo -e "  Pustakawan : ${YELLOW}budi@perpus.id${NC}       / ${YELLOW}pustakawan123${NC}"
echo ""
echo -e "${BLUE}================================================${NC}"
echo -e "${GREEN}  JALANKAN SERVER${NC}"
echo -e "${BLUE}================================================${NC}"
echo -e "  ${YELLOW}php artisan serve${NC}"
echo ""
echo -e "  Lalu buka: ${BLUE}http://localhost:8000${NC}"
echo ""
