<?php
/**
 * WordPress Test Content Generator
 * 
 * This script creates demo pages that showcase the theme's design and functionality.
 * Run this via WordPress admin or directly in functions.php
 * 
 * Add this to functions.php temporarily to auto-create content on theme activation:
 * add_action('after_switch_theme', 'create_boilerplate_test_content');
 */

function create_boilerplate_test_content() {
    // Prevent duplicate creation
    if (get_option('boilerplate_test_content_created')) {
        return;
    }

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
- Build reusable UI components

## Getting Started

Everything is organized by component type: layouts, content blocks, and UI components. Each new component starts from a base template and follows consistent naming conventions.

### Component Types

#### Layout Components
Used for page structure, hero sections, and non-semantic wrappers.

#### Content Blocks
Render parsed sections automatically from your content structure.

#### UI Components
Small reusable elements like buttons, chips, and tags.

## Design System

The theme uses neutral placeholder copy and tokens that you customize for each project. No hardcoded brand-specific content means maximum flexibility.

### Color & Typography

Colors and typography are defined in neutral tokens. Edit `inc/boilerplate-defaults.php` to customize for your project.

## Multiple Sections

You can add as many H2 sections as needed. Each one renders as a separate content block, making complex layouts simple to build.

## Ready to Explore?

Check out the sample pages to see the theme in action. Edit any page to see how content structure affects rendering.',
            'post_type'     => 'page',
            'post_status'   => 'publish',
        ],
        [
            'post_title'    => 'About',
            'post_name'     => 'about',
            'post_content'  => '# About Our Studio

Learn about who we are and what we do.

This is the summary text that appears before the first section. It provides an overview and context for the page.

## Our Story

We believe in building digital experiences that are thoughtful, accessible, and purposeful. This theme is designed to help teams create beautiful websites without reinventing the wheel.

### Our Mission

To provide a flexible, neutral foundation that scales with your needs. Whether you\'re building a small site or a complex digital experience, this theme adapts to your requirements.

#### Core Values

- Simplicity in design
- Flexibility in architecture  
- Accessibility first
- Performance matters

## Our Team

Though this is a sample page, imagine featuring your team members here. Multiple sections organize content clearly.

## Services We Offer

When you build your site, you can list your specific services. The theme supports unlimited sections and subsections.

## Get In Touch

Ready to work together? Contact us through your WordPress site.

### Ways to Reach Us

- Email: contact@example.com
- Phone: (555) 123-4567
- Address: Your City, State',
            'post_type'     => 'page',
            'post_status'   => 'publish',
        ],
        [
            'post_title'    => 'Services',
            'post_name'     => 'services',
            'post_content'  => '# What We Offer

Explore our comprehensive service offerings.

Detailed descriptions help customers understand what you do. This summary section provides context before diving into specific services.

## Web Design

We create beautiful, functional websites that work across all devices. Our design process focuses on user experience and business goals.

### Design Process

1. Discovery & Strategy
2. Wireframing & Prototyping
3. Visual Design
4. Development & Testing

#### Latest Projects

Our recent work showcases modern design trends and clean interfaces.

## Development

From frontend to backend, we build robust solutions that perform.

### Technologies We Use

- Modern HTML & CSS
- JavaScript frameworks
- WordPress & PHP
- Database optimization

#### Performance First

Every project is optimized for speed and efficiency.

## Branding

Strong brands make lasting impressions. We help define and refine your visual identity.

### Brand Strategy

We work with you to define your brand voice, values, and visual language.

## Consultation

Not sure where to start? Let\'s talk about your project.

### How It Works

Schedule a free consultation to discuss your needs and goals.',
            'post_type'     => 'page',
            'post_status'   => 'publish',
        ],
        [
            'post_title'    => 'Portfolio',
            'post_name'     => 'portfolio',
            'post_content'  => '# Our Work

Showcase projects and case studies here.

This sample portfolio page demonstrates how to structure project information using the theme\'s content blocks.

## Project One

A detailed case study of a successful project. Include the client, challenge, solution, and results.

### The Challenge

Describe what the client needed and what obstacles existed.

### The Solution

Explain your approach and how you solved the problem.

#### Key Outcomes

- Achievement 1
- Achievement 2
- Achievement 3

## Project Two

Another example showcasing different industry or approach.

### Details

Use H3 headers to break down project information into digestible sections.

#### Results & Impact

Quantify the impact of your work. Numbers and specific outcomes are more compelling.

## Project Three

One more portfolio piece to round out your showcase.

### About the Project

Tell the story of what made this project special or unique.

## Let\'s Work Together

Ready to start your next project?

### Contact Us

We\'d love to hear about your ideas and discuss how we can help bring them to life.',
            'post_type'     => 'page',
            'post_status'   => 'publish',
        ],
        [
            'post_title'    => 'Blog',
            'post_name'     => 'blog',
            'post_content'  => '# Latest Articles

Stay updated with our latest thoughts and insights.

We share knowledge and industry insights through our blog. Check back regularly for new content.

## Content & Strategy

Learn how to develop an effective content strategy for your website.

### Why Content Matters

Good content drives engagement and helps people understand your value.

### Getting Started

Start by defining who you\'re writing for and what problems you solve.

#### Content Types

- Blog articles
- Case studies
- Guides
- Tutorials

## Theme Architecture

Explore how this theme is organized and built.

### Component-Based Design

Everything is built from reusable, modular components that work together.

## Web Performance

Making fast, efficient websites that delight users.

### Performance Tips

- Optimize images
- Minimize CSS & JS
- Use caching strategically
- Monitor metrics

#### Tools We Use

Modern tools help us measure and improve performance.',
            'post_type'     => 'page',
            'post_status'   => 'publish',
        ],
        [
            'post_title'    => 'Design Components',
            'post_name'     => 'design-components',
            'post_content'  => '# Design System Components

Explore all available UI components and design patterns.

This page demonstrates the reusable components available in your theme. Each section showcases different component types and their variations.

## Buttons

Buttons are the primary call-to-action element across your site.

### Button Variants

Primary buttons for main actions, secondary for alternatives, and tertiary for less critical actions.

#### Button States

- Default
- Hover
- Active
- Disabled

## Cards & Containers

Cards organize related content into scannable, self-contained modules.

### Card Variations

Use cards for team members, services, portfolio items, or testimonials.

#### Card Content

Cards can include images, headings, descriptions, and action buttons.

## Forms

Collect information from visitors with accessible, well-designed forms.

### Form Elements

Text inputs, textareas, selects, checkboxes, and radio buttons all follow consistent styling.

#### Accessibility

All form elements include proper labels and ARIA attributes.',
            'post_type'     => 'page',
            'post_status'   => 'publish',
        ],
        [
            'post_title'    => 'Contact',
            'post_name'     => 'contact',
            'post_content'  => '# Get In Touch

We\'d love to hear from you. Reach out with questions or to discuss your project.

Whether you have a quick question or want to discuss a full project, we\'re here to help. Multiple ways to connect with us below.

## Contact Information

Multiple ways to reach our team depending on your preference.

### Email

Send us an email and we\'ll get back to you as soon as possible.

contact@example.com

### Phone

Call us during business hours to discuss your project.

(555) 123-4567

#### Hours

Monday - Friday: 9am - 5pm
Saturday - Sunday: By appointment

## Visit Us

Come by our office to meet the team in person.

### Location

123 Main Street
Your City, State 12345

## Send a Message

Use the form below to send us a direct message.

### Quick Contact

Tell us about your project, ask questions, or request more information. We typically respond within 24 hours.',
            'post_type'     => 'page',
            'post_status'   => 'publish',
        ],
    ];

    foreach ($pages as $page_data) {
        // Check if page already exists
        $existing = get_page_by_path($page_data['post_name']);
        if ($existing) {
            continue;
        }

        // Create the page
        $post_id = wp_insert_post([
            'post_title'    => $page_data['post_title'],
            'post_name'     => $page_data['post_name'],
            'post_content'  => $page_data['post_content'],
            'post_type'     => $page_data['post_type'],
            'post_status'   => $page_data['post_status'],
        ]);

        if (!is_wp_error($post_id)) {
            error_log("Created page: {$page_data['post_title']} (ID: $post_id)");
        }
    }

    // Create sample blog posts
    $posts = [
        [
            'post_title'    => 'Getting Started with the Boilerplate Theme',
            'post_content'  => 'This post explains how to get started with your new theme. Learn about the component system, how to create new sections, and best practices for organizing your content.',
            'post_type'     => 'post',
            'post_status'   => 'publish',
        ],
        [
            'post_title'    => 'Understanding Component Architecture',
            'post_content'  => 'Dive deep into how the theme\'s component system works. We\'ll cover layout components, content blocks, UI components, and how they work together to create flexible layouts.',
            'post_type'     => 'post',
            'post_status'   => 'publish',
        ],
        [
            'post_title'    => 'Best Practices for Content Organization',
            'post_content'  => 'Learn how to structure your content for maximum impact. Proper heading hierarchy, logical section organization, and clear calls-to-action create better user experiences.',
            'post_type'     => 'post',
            'post_status'   => 'publish',
        ],
    ];

    foreach ($posts as $post_data) {
        $existing = get_page_by_title($post_data['post_title'], OBJECT, 'post');
        if ($existing) {
            continue;
        }

        wp_insert_post($post_data);
    }

    // Mark that we've created content
    update_option('boilerplate_test_content_created', true);
    error_log('Boilerplate test content created successfully');
}

// Uncomment the line below to run on theme activation
// add_action('after_switch_theme', 'create_boilerplate_test_content');
