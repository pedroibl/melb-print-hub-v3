# 10 Detailed PR Search Prompts for Your Laravel Project

### 1. **Authentication & Authorization Patterns**
**Focus:** Security implementation patterns
**Prompt:** "How have authentication and authorization been implemented in previous PRs? Show me examples of middleware usage, guard configurations, and policy implementations from merged PRs. Include any security improvements or vulnerability fixes."
**Expected Output:** Code examples of custom middleware, authentication flows, role-based access control implementations, and security patches with explanations of the patterns used.

### 2. **Database Migration & Schema Evolution**
**Focus:** Database structure changes and migration patterns
**Prompt:** "What database migrations and schema changes have been implemented across PRs? Show me examples of table creation, column modifications, index additions, and any database refactoring patterns used in this Laravel project."
**Expected Output:** Migration files, schema changes, foreign key relationships, and database optimization strategies with timestamps and reasoning.

### 3. **API Endpoint Development Patterns**
**Focus:** RESTful API implementation consistency
**Prompt:** "How are API endpoints structured and implemented in previous PRs? Show me examples of controller methods, request validation, response formatting, and API versioning approaches used in this project."
**Expected Output:** Controller code, validation rules, API resource transformers, and consistent response patterns with HTTP status codes.

### 4. **Testing Strategy Evolution**
**Focus:** Test coverage and testing patterns
**Prompt:** "What testing approaches and patterns have been used across PRs? Show me examples of unit tests, feature tests, database testing, and any testing utilities or factories created for this Laravel project."
**Expected Output:** Test files, PHPUnit configurations, factory definitions, and testing best practices with coverage improvements.

### 5. **Service Layer & Business Logic Organization**
**Focus:** Code architecture and separation of concerns
**Prompt:** "How is business logic organized in service classes or repositories across PRs? Show me examples of service layer implementations, dependency injection patterns, and how complex business operations are structured."
**Expected Output:** Service classes, repository patterns, interface implementations, and dependency injection configurations.

### 6. **Frontend Integration & Blade Templates**
**Focus:** View layer and frontend implementation
**Prompt:** "How are Blade templates and frontend components structured in previous PRs? Show me examples of component organization, asset compilation, and any JavaScript/CSS integration patterns used."
**Expected Output:** Blade template structures, component hierarchies, asset pipeline configurations, and frontend-backend integration examples.

### 7. **Error Handling & Logging Strategies**
**Focus:** Exception management and debugging approaches
**Prompt:** "What error handling and logging strategies have been implemented across PRs? Show me examples of custom exception classes, error reporting, logging configurations, and debugging tools used."
**Expected Output:** Exception handlers, logging configurations, error reporting mechanisms, and debugging utilities with context.

### 8. **Performance Optimization Techniques**
**Focus:** Code performance and optimization patterns
**Prompt:** "What performance optimizations and caching strategies have been implemented in previous PRs? Show me examples of query optimization, caching implementations, and any performance monitoring solutions added."
**Expected Output:** Optimized queries, caching strategies, performance monitoring code, and benchmark improvements with metrics.

### 9. **Configuration & Environment Management**
**Focus:** Application configuration and deployment
**Prompt:** "How are configuration files and environment-specific settings managed across PRs? Show me examples of config file changes, environment variable usage, and any deployment-related configurations."
**Expected Output:** Config files, environment variable definitions, deployment scripts, and environment-specific customizations.

### 10. **Third-Party Integration Patterns**
**Focus:** External service integrations and packages
**Prompt:** "What third-party services and packages have been integrated in previous PRs? Show me examples of API integrations, package installations, service provider configurations, and any custom wrapper implementations."
**Expected Output:** Integration code, service provider configurations, API client implementations, and package usage examples with documentation.

**Usage Tip:** Use `@[PR number]` to fetch specific PRs into context when asking these questions for more detailed analysis.

___

Based on the documentation, here's a detailed prompt for using PR Search to solve difficult bugs in your Laravel project:

## **PR Search Prompt for Difficult Bug Solving**

**Focus:** Historical bug resolution patterns and debugging methodologies
**Prompt:** "What difficult bugs or complex issues have been solved in previous PRs? Show me examples of debugging approaches, error investigation techniques, and resolution strategies used in this Laravel project. Include any performance issues, security vulnerabilities, race conditions, or integration problems that were identified and fixed. Focus on PRs that demonstrate thorough problem analysis, root cause identification, and comprehensive testing of fixes."

**Expected Output:** 
- **Bug Investigation Process:** Step-by-step debugging methodologies used in past PRs
- **Error Patterns:** Common types of difficult bugs encountered (database deadlocks, memory leaks, API integration failures, etc.)
- **Diagnostic Tools:** Logging strategies, debugging tools, and monitoring approaches used
- **Root Cause Analysis:** Examples of how complex issues were traced to their source
- **Fix Implementation:** Code changes that resolved difficult problems with explanations
- **Testing Strategies:** How fixes were validated and regression testing was performed
- **Prevention Measures:** Code improvements or architectural changes made to prevent similar issues

**Usage Examples:**
- `@[PR 45]` - Fetch specific PR that solved a complex database performance issue
- `@[commit abc123]` - Reference the exact commit that fixed a critical security vulnerability
- `@[branch hotfix/memory-leak]` - Pull context from a branch that addressed a difficult memory issue

**Additional Context Questions:**
- "What debugging tools and techniques were most effective in these PRs?"
- "How were intermittent or hard-to-reproduce bugs approached and resolved?"
- "What patterns emerge from the most challenging bug fixes in this project's history?"
- "Which PRs demonstrate the best practices for investigating and documenting complex issues?"

This prompt leverages PR Search's ability to automatically index merged PRs and fetch historical context, helping you learn from past debugging successes and apply proven problem-solving approaches to current difficult bugs.