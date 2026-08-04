---
name: sprint-close
description: Close the current sprint and generate all engineering artifacts.
---

# Purpose

Close the current sprint following the project's engineering standards.

# Inputs

- Sprint number
- Completed tasks
- Architectural decisions
- Implemented features

# Responsibilities

- Update CHANGELOG.md
- Generate sprint documentation
- Suggest Git tag
- Suggest Conventional Commit
- Update roadmap progress
- Update backlog
- Detect whether a new ADR is required
- Suggest README updates
- Update AI context when necessary

# Output

Generate:

- CHANGELOG entry
- docs/planning/sprint-XX.md
- Git Tag
- Release summary
- Next Sprint proposal

# Rules

Never invent completed work.

Always summarize decisions separately from implementation.

Use Keep a Changelog format.

Follow Semantic Versioning.

# Checklist

- [ ] CHANGELOG updated
- [ ] Sprint documented
- [ ] Roadmap updated
- [ ] Backlog updated
- [ ] ADR evaluated
- [ ] Version suggested
