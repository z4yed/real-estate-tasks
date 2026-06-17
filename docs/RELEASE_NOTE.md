# Release Note

_Last updated: 18 June 2026_

- **Live site:** [real-estate-tasks.zayed-hassan.com](https://real-estate-tasks.zayed-hassan.com)
- **Source code:** [github.com/z4yed/real-estate-tasks](https://github.com/z4yed/real-estate-tasks)

---

## What's New

### Contact Management for Agents

Agents now have a dedicated space to manage their own contacts.

- Add, edit, and view contacts from the agent panel.
- Each contact opens into a clean detail view with all their information in one place.
- Contacts are tied to the agent who owns them, so everyone sees only their own list.

### Task Management

Tasks are now built right into the agent workflow.

- Create and track tasks against contacts.
- Manage tasks directly from a contact's detail page, with no jumping between screens.
- Mark work as complete and keep a clear record of what's done.

### Agent Dashboard & Stats

A new dashboard gives agents an at-a-glance view of their day.

- **Stats overview** showing key numbers up top.
- **Completed task stats** so progress is easy to see.
- A **tasks table** front and center for quick access to what needs attention.

### Tabbed Views

Lists are now organised into tabs, making it faster to filter and focus on the right records without extra clicks.

### Layout Polish

- The **Agent form** in the admin panel now spans the full width of the page for a cleaner, more comfortable layout.

---

## How It Works

A typical day in the platform flows like this:

1. An agent signs in and lands on their **dashboard**, seeing their stats and outstanding tasks at a glance.
2. They open the **contacts** area to add a new contact or review an existing one.
3. From a contact's detail page, they create and track **tasks** for that person.
4. As work gets done, tasks are marked complete and the **dashboard stats** update to reflect progress.

---

## How Updates Reach the Live Site

Every change goes through an automated pipeline, so releases are consistent and hands-off:

1. **Review & merge:** changes are reviewed in a pull request and merged into the main branch.
2. **Build:** a fresh Docker image is automatically built and published to the registry.
3. **Deploy:** the server pulls the new image and rolls it out over a secure connection.

Flow at a glance:

```
Pull request  ->  Merge to main  ->  Build image  ->  Deploy to server  ->  Live
```

The result: once a change is approved, it's live on [real-estate-tasks.zayed-hassan.com](https://real-estate-tasks.zayed-hassan.com) within minutes, with no manual steps and no downtime surprises.

You can follow each build and deployment in the pipeline history here: [github.com/z4yed/real-estate-tasks/actions](https://github.com/z4yed/real-estate-tasks/actions).

---

Developed by [Zayed Hassan](https://zayed-hassan.com).
