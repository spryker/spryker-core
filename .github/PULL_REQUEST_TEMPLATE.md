### Overview
- Developer(s): @your_username

- Ticket: URL_HERE

- Project PR: URL_HERE

- Bundles to release:

   Bundle       | Expected Release Type | Constraints |
   :----------- | :------------         | :------------
   BundleA      | major or new          |                    |
   BundleB      | minor                 | BundleA: new major |
   BundleC      | patch                 | BundleD: 2.2.0     |

   `patch` requires 1, `minor` 2 and `major` 3 reviewers.

- Recommended reviewers: @reviewer_usernames

-----------------------------------------

#### Bundle MajorBundleA (e.g. "Bundle Cms")

_**Maxi-Major:** New or fully refactored bundle._

Def of done (by responsible developer):
- [ ] All methods in facade and all plugin-interfaces provide an [API-doc](https://academy.spryker.com/display/CORE/Definition+of+API)
- [ ] All [dependencies](https://academy.spryker.com/display/CORE/Bundle+Dependency+Guidelines) are defined, checked and corrected
- [ ] There are no violations of the architecture rules
- [ ] All deprecations are removed
- [ ] All new or heavily changed classes get an "A" rating, except Facades, Factories, QueryContainers and Tests
- [ ] All new or changed business logic is covered by unit tests (in Zed business layer)
- [ ] If there are new OS components, they must be approved by legal department

##### Change log
{Add here highlights of what are the important new things that come with this feature}. 
- I added this.
- I added this.

-----------------------------------------

#### Bundle MajorBundleA

_**Mini-Major:** Added functionality with incompatible API changes_

Def of done (by responsible developer):
- [ ] All methods in facade and all plugin-interfaces provide an [API-doc](https://academy.spryker.com/display/CORE/Definition+of+API)
- [ ] New code fits to the architecture rules
- [ ] All deprecations are removed
- [ ] All new or heavily changed classes get an "A" rating (Scrutinizer), except Facades, Factories, QueryContainers and Tests
- [ ] All new or changed business logic is covered by unit tests (in Zed business layer)
- [ ] If there are new OS components, they must be approved by legal department

##### Change log
{Add here highlights of what are the important new things that come with this feature.} 
- I added this.
- I added this.

-----------------------------------------

#### Bundle MinorBundleB

_**Minor:** Added functionality in a backwards-compatible manner_

Def of done (by responsible developer):
- [ ] All changes are backward-compatible. Outdated code is marked as deprecated
- [ ] New code fits to the architecture rules
- [ ] All new or heavily changed classes get an "A" rating (Scrutinizer), except Facades, Factories, QueryContainers and Tests
- [ ] All new or changed business logic is covered by unit tests (in Zed business layer)
- [ ] If there are new OS components, they must be approved by legal department

##### Change log
Improvements

{Some explanation of what has been improved.}
- I added this.

-----------------------------------------

#### Bundle PatchBundleC

_**Patch:** Backwards-compatible bug fix_

Def of done (by responsible developer):
- [ ] All changes are backward-compatible. Outdated code is marked as deprecated
- [ ] The change is isolated and not mixed with any cleanups or other changes
- [ ] New code fits to the architecture rules
- [ ] All new or heavily changed classes get an "A" rating (Scrutinizer), except Facades, Factories, QueryContainers and Tests
- [ ] All new or changed business logic is covered by unit tests (in Zed business layer)
- [ ] If there are new OS components, they must be approved by legal department

##### Change log
Bugfixes

{Some explanation of what was wrong.}
- I fixed this.
