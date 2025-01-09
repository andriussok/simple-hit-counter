#!/bin/bash

# Set plugin name
PLUGIN_NAME="simple-hit-counter"

# Clean up previous builds
echo "Cleaning up old build..."
rm -rf build
mkdir build

# Copy plugin files to the build directory
echo "Copying plugin files..."
cp -R . build/$PLUGIN_NAME

# Navigate to the build directory
cd build/$PLUGIN_NAME
rm -rf build

# Install production dependencies
echo "Installing production dependencies..."
composer install --no-dev

# Remove unnecessary development files
echo "Removing development files..."
rm -f composer.json composer.lock .gitignore build.sh README.md
rm -rf .git

# Navigate back to the build folder
cd ..

# Create a zip file
echo "Creating zip file..."
zip -r ${PLUGIN_NAME}.zip $PLUGIN_NAME

# Cleanup temporary build folder (optional)
# Uncomment the next line if you want to remove the temporary build folder after creating the zip
rm -rf $PLUGIN_NAME

# Done
echo "Build complete: build/${PLUGIN_NAME}.zip"
