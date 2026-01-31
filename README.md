About the project 

Project Overview
The blogging platform project represents a comprehensive web application designed to facilitate content creation, sharing, and social interaction. Built using core PHP along with HTML, CSS, and JavaScript, the platform provides users with a complete blogging ecosystem that includes user management, content creation with rich text editing, profile customization, and social engagement features.
Design Philosophy
The visual design draws heavy inspiration from pixel-art arcade games of the 2010s, particularly games like Harvest Town. This design choice aims to evoke nostalgia and familiarity among users. The pixel-art aesthetic creates a warm, approachable interface that stands apart from minimalist designs common in contemporary web applications while maintaining modern functionality.
The platform was designed with intuitive navigation as a core principle. By leveraging nostalgic visual elements, the interface becomes immediately recognizable and comfortable for users, reducing the learning curve and enhancing overall user engagement. The retro aesthetic does not compromise functionality but rather enhances it by creating a cohesive and memorable user experience.
Target Audience
The platform is designed for content creators, bloggers, writers and readers who appreciate a unique visual aesthetic combined with robust functionality. The target audience includes individuals seeking an alternative to mainstream blogging platforms, those who value creative expression, and users who appreciate nostalgic design elements.
Technical Specifications
Frontend Technologies:
•	HTML5 for semantic markup and structure
•	CSS3 for styling and responsive design
•	JavaScript for client-side interactivity
•	Quill.js for rich text editing capabilities
Backend Technologies:
•	Core PHP for server-side logic
•	MySQL for database management
Additional Tools:
•	AJAX for asynchronous data loading
•	Session management for user authentication
•	File upload handling for avatars and images
System Architecture
The platform follows a traditional three-tier architecture consisting of the presentation layer (HTML, CSS, JavaScript), application layer (PHP), and data layer (MySQL). This architecture ensures separation of concerns, maintainability, and scalability. User requests are processed through PHP scripts that interact with the database and return dynamic content to the browser.
Development Environment
The development environment setup included modern tools and practices that align with industry standards. Version control was managed using Git, code editors included Visual Studio Code, and local development was conducted using XAMPP for PHP and MySQL integration. This professional setup enabled efficient development and collaboration.
Features and Implementation
User Registration and Authentication:
The platform implements a secure user registration and authentication system. New users can create accounts by providing essential information including username, email, and password. Passwords are hashed using industry-standard encryption algorithms before storage. The authentication system validates user credentials during login and maintains session state to provide personalized experiences.
Key Security Features:
•	Password hashing algorithm using bcrypt
•	Session-based authentication with secure tokens
•	Input validation and sanitization
•	Protection against SQL injection using prepared statements
Rich Text Editor Integration:
The integration of Quill.js provides users with professional-grade text editing capabilities. This WYSIWYG editor allows users to format blog posts with various styling options including headings, bold and italic text, lists, links, and embedded media. The editor provides a clean interface that seamlessly integrates with the platform's pixel-art aesthetic.
Editor Capabilities:
•	Text formatting (bold, italic, underline, strikethrough)
•	Heading styles and font customization
•	Bulleted and numbered lists
•	Hyperlink insertion and management
•	Image embedding and alignment
•	Code block formatting for technical content
Post Management (CRUD Operations):
The platform implements complete CRUD (Create, Read, Update, Delete) operations for blog posts. Users can create new posts using the rich text editor, view their published posts in an organized dashboard, edit existing content to make updates or corrections, and delete posts they no longer wish to maintain. This functionality provides complete control over content management.
Post Management Features:
•	Create new blog posts with rich formatting
•	View posts in a user-friendly layout with pagination
•	Edit and update existing posts while preserving formatting
•	Delete unwanted posts with confirmation dialogs
•	Category assignment for better organization
User Profile Customization:
Users can personalize their profiles through a draggable profile window interface. This innovative feature allows users to change avatar images, update personal information such as bio and modify account settings including password changes. The draggable interface enhances user experience by providing flexible positioning of the profile editor.
Customization Options:
•	Avatar image upload with file validation
•	Profile banner customization
•	Bio and personal information updates
•	Password change with current password verification
•	Draggable profile editor interface using JavaScript
•	Real-time preview of profile changes
Social Engagement Features:
The platform incorporates social features to foster community engagement. Users can like posts to show appreciation for content, leave comments to engage in discussions, and view other users' profiles to discover more content. These social features create an interactive community environment that encourages participation and content discovery.
Social Features:
•	Post liking system with real-time like counters
•	Commenting functionality on posts
•	Comment editing and deletion for comment authors
•	User profile viewing with post history
•	Activity indicators for recent interactions
Search and Filter Functionality:
The platform includes comprehensive search and filtering capabilities to help users discover content efficiently. A simple user search feature allows finding specific bloggers by username, while the category-based filtering system enables content discovery by topic. Posts can be sorted by various criteria including publication date and popularity, facilitating efficient content navigation.

Search and Filter Options:
•	User search by username with autocomplete
•	Category-based post filtering
•	Sort by newest posts (most recent first)
•	Sort by oldest posts (chronological order)
•	Sort by number of likes (popularity-based)
