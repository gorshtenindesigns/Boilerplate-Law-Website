<?php
/**
 * Quick Test Content Generator for Boilerplate Theme
 * Place in wp-content/themes/boilerplate-update/ and run: php generate-content.php
 */

define('WP_USE_THEMES', false);
require('../../../wp-load.php');

$pages = [
    [
        'post_title'    => 'Home',
        'post_name'     => 'home',
        'post_content'  => '# Welcome to Boilerplate Theme

This is your homepage. Edit this content to customize it.

## Features & Capabilities

Our theme is built on a neutral, component-based architecture that makes it easy to create beautiful, structured layouts.

### What You Can Do

- Create structured pages with multiple sections
- Use layout components for page shells and hero sections
- Render content blocks automatically from H2 sections
- Build reusable UI components',
        'post_type'     => 'page',
        'post_status'   => 'publish',
    ],
    [
        'post_title'    => 'About',
        'post_name'     => 'about',
        'post_content'  => '# About Our Studio

Learn about who we are and what we do.

## Our Story

We believe in building digital experiences that are thoughtful, accessible, and purposeful.

### Our Mission

To provide a flexible, neutral foundation that scales with your needs.

## Our Team

Though this is a sample page, imagine featuring your team members here.',
        'post_type'     => 'page',
        'post_status'   => 'publish',
    ],
    [
        'post_title'    => 'Services',
        'post_name'     => 'services',
        'post_content'  => '# What We Offer

Explore our comprehensive service offerings.

## Web Design

We create beautiful, functional websites that work across all devices.

### Design Process

1. Discovery & Strategy
2. Wireframing & Prototyping
3. Visual Design
4. Development & Testing

## Development

From frontend to backend, we build robust solutions that perform.',
        'post_type'     => 'page',
        'post_status'   => 'publish',
    ],
    [
        'post_title'    => 'Portfolio',
        'post_name'     => 'portfolio',
        'post_content'  => '# Our Work

Showcase projects and case studies here.

## Project One

A detailed case study of a successful project.

### The Challenge

Describe what the client needed.

### The Solution

Explain your approach and how you solved the problem.',
        'post_type'     => 'page',
        'post_status'   => 'publish',
    ],
    [
        'post_title'    => 'Contact',
        'post_name'     => 'contact',
        'post_content'  => '# Get In Touch

We\'d love to hear from you.

## Contact Information

Multiple ways to reach our team.

### Email

Send us an email at contact@example.com

### Phone

Call us at (555) 123-4567',
        'post_type'     => 'page',
        'post_status'   => 'publish',
    ],
];

echo "Creating test pages...\n";

foreach ($pages as $page) {
    $existing = get_page_by_path($page['post_name']);
    if ($existing) {
        echo "✓ {$page['post_title']} already exists (ID: {$existing->ID})\n";
        continue;
    }
    
    $post_id = wp_insert_post($page);
    if (!is_wp_error($post_id)) {
        echo "✓ Created: {$page['post_title']} (ID: $post_id)\n";
    } else {
        echo "✗ Error creating {$page['post_title']}: " . $post_id->get_error_message() . "\n";
    }
}

echo "\nDone! Visit http://law-by-yan.local to see your pages.\n";
echo "Visit http://law-by-yan.local/wp-admin to edit them.\n";
?>