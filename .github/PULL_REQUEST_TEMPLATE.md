#### Overview
- Developers: @your_names

- Issue: [#main_issue_number](https://github.com/spryker/spryker/issues/issue_number)

- Bundles to release:

   Bundle       | Expected Release Type | Constraints
   :----------- | :------------         | :------------
   BundleA      | major or new          | 
   BundleB      | minor                 | BundleA: new major
   BundleC      | patch                 | BundleD: 2.2.0

   `patch` requires 1, `minor` 2 and `major` 3 reviewers.

-----------------------------------------

#### Bundle MajorBundleA (e.g. "Bundle Cms")
- [ ] API contract checked
- [ ] Unit tests
- [ ] Functional (Facade) tests
- [ ] Scrutinizer score (>= 9.7)
- [ ] Documentation [PR](https://github.com/spryker/spryker.github.io/pull/pr_number)
- [ ] No open tickets that requires a major release
- [ ] Legal check for external dependencies

###### Change log
Changes go here as list items and with a header (Improvements/Bugfixes). Those will be copied to the release log for this bundle.

-----------------------------------------

#### Bundle MinorBundleB
- [ ] Tests for the feature
- [ ] Scrutinizer score is not worse
- [ ] No open tickets that could be in minor release
- [ ] Legal check for external dependencies

###### Change log
Features
- I added this.

-----------------------------------------

#### Bundle PatchBundleC
- [ ] Tests for the patch
- [ ] Scrutinizer score is not worse

###### Change log
Bugfixes
- I fixed this.
