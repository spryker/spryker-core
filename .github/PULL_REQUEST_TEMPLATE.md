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

#### Feature Release Notes

{These are necessary for more than a single bundle release, and are usually combined feature release notes.}
{Include here a generic description that describes what the feature is about, what new functionality is there, if needed also high level how to use tips.}
{For improvements include what addition was made to existing feature or how an already existing functionality is made better.}
{For a fix state what was former behavior, what was breaking or broken and what is the fixed current behavior.}  
{Make sure you use proper English sentences.}

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
- Xyz has been added.
- Xyz has been added.

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
- Xyz has been added.

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
- Xyz has been fixed.
